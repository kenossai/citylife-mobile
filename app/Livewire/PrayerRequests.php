<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class PrayerRequests extends Component
{
    public string $tab = 'browse';

    public string $name = '';

    public string $category = 'Personal';

    public string $request = '';

    public bool $isPrivate = false;

    public ?string $notice = null;

    public ?string $error = null;

    public bool $signedIn = false;

    public bool $usesWebsite = false;

    /** @var array<int, array<string, mixed>> */
    public array $myRequests = [];

    /** @var array<int, string> */
    public array $categories = ['Personal', 'Family', 'Health', 'Spiritual', 'Work', 'Church', 'Praise Report'];

    /** @var array<int, array{id: int, name: string, date: string, category: string, text: string, prayers: int, prayed: bool}> */
    public array $requests = [
        ['id' => 1, 'name' => 'Sarah Johnson', 'date' => 'Jan 9, 2026', 'category' => 'Health', 'text' => 'Please pray for my father who is scheduled for surgery tomorrow. Praying for the doctors and a successful procedure.', 'prayers' => 45, 'prayed' => false],
        ['id' => 2, 'name' => 'Michael Chen', 'date' => 'Jan 8, 2026', 'category' => 'Spiritual', 'text' => 'Requesting prayers for strength and wisdom as I navigate a difficult decision in my life.', 'prayers' => 23, 'prayed' => false],
        ['id' => 3, 'name' => 'David Kim', 'date' => 'Jan 7, 2026', 'category' => 'Praise Report', 'text' => 'Thank God for answering our prayers for our new home!', 'prayers' => 21, 'prayed' => false],
    ];

    /** @var array<int, int> */
    public array $mine = [];

    /** @var array<int, string> */
    public array $guidelines = [
        'Be respectful and considerate in your requests',
        'Share authentic needs but protect privacy',
        'Come back to share praise reports when prayers are answered',
    ];

    public function mount(): void
    {
        $this->usesWebsite = ContentApi::isConfigured();

        if (! $this->usesWebsite) {
            return;
        }

        $member = MemberSession::member();
        $this->signedIn = $member !== null;
        $this->name = $member['first_name'] ?? '';

        $this->requests = array_map(
            fn (array $item): array => $this->fromApi($item) + ['prayed' => in_array($item['id'], session('citylife.prayed_requests', []), true)],
            ContentApi::get('prayer-requests', 20) ?? [],
        );

        $this->loadMine();
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function fromApi(array $item): array
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'date' => Carbon::parse($item['created_at'])->format('M j, Y'),
            'category' => $item['category'],
            'text' => $item['text'],
            'prayers' => $item['prayers'],
            'prayed' => false,
            'status' => $item['status'] ?? 'approved',
            'private' => $item['is_private'] ?? false,
        ];
    }

    private function loadMine(): void
    {
        if (! $this->signedIn) {
            $this->myRequests = $this->refreshSavedRequests();

            return;
        }

        $result = MemberSession::request('GET', 'prayer-requests/mine');

        if ($result['status'] === 401) {
            $this->signedIn = false;
            $this->myRequests = $this->refreshSavedRequests();

            return;
        }

        $this->myRequests = array_map(fn (array $item): array => $this->fromApi($item), $result['data'] ?? []);
    }

    /**
     * A guest's requests are kept on the device, so mark the ones that now appear in the public list as approved.
     *
     * @return array<int, array<string, mixed>>
     */
    private function refreshSavedRequests(): array
    {
        $approved = collect($this->requests)->keyBy('id');

        $saved = array_map(function (array $item) use ($approved): array {
            $live = $approved->get($item['id']);

            return $live ? [...$item, 'status' => 'approved', 'prayers' => $live['prayers']] : $item;
        }, session('citylife.my_prayer_requests', []));

        session()->put('citylife.my_prayer_requests', $saved);

        return $saved;
    }

    /** @return array<int, array<string, mixed>> */
    #[Computed]
    public function mineItems(): array
    {
        return $this->usesWebsite
            ? $this->myRequests
            : array_values(array_filter($this->requests, fn ($item) => in_array($item['id'], $this->mine, true)));
    }

    public function showTab(string $tab): void
    {
        if (in_array($tab, ['browse', 'mine', 'submit'], true)) {
            $this->tab = $tab;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'in:'.implode(',', $this->categories)],
            'request' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        if ($this->usesWebsite) {
            $this->submitToWebsite();

            return;
        }

        $id = count($this->requests) + 1;

        array_unshift($this->requests, [
            'id' => $id,
            'name' => trim($this->name) !== '' ? trim($this->name) : 'Anonymous',
            'date' => now()->format('M j, Y'),
            'category' => $this->category,
            'text' => trim($this->request),
            'prayers' => 0,
            'prayed' => false,
        ]);
        $this->mine[] = $id;

        $this->reset('name', 'request');
        $this->category = 'Personal';
        $this->tab = 'mine';
    }

    private function submitToWebsite(): void
    {
        $this->error = null;

        $result = MemberSession::request('POST', 'prayer-requests', [
            'name' => trim($this->name),
            'category' => $this->category,
            'request' => trim($this->request),
            'is_private' => $this->isPrivate,
        ]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        if (! $this->signedIn) {
            $saved = session('citylife.my_prayer_requests', []);
            array_unshift($saved, $this->fromApi($result['data']));
            session()->put('citylife.my_prayer_requests', $saved);
        }

        $this->notice = $this->isPrivate
            ? 'Thank you. Your private request has gone to our pastoral team.'
            : 'Thank you. Your request will appear in the app once our team has reviewed it.';

        ContentApi::forget('prayer-requests');

        $this->reset('request', 'isPrivate');
        $this->name = MemberSession::member()['first_name'] ?? '';
        $this->category = 'Personal';
        $this->loadMine();
        $this->tab = 'mine';
    }

    public function pray(int $id): void
    {
        if ($this->usesWebsite) {
            $this->prayOnWebsite($id);

            return;
        }

        foreach ($this->requests as $index => $prayerRequest) {
            if ($prayerRequest['id'] === $id && ! $prayerRequest['prayed']) {
                $this->requests[$index]['prayed'] = true;
                $this->requests[$index]['prayers']++;
            }
        }
    }

    private function prayOnWebsite(int $id): void
    {
        $prayed = session('citylife.prayed_requests', []);

        if (in_array($id, $prayed, true)) {
            return;
        }

        $result = ContentApi::send('POST', "prayer-requests/{$id}/pray");

        if (! $result['ok']) {
            return;
        }

        session()->put('citylife.prayed_requests', [...$prayed, $id]);

        foreach ($this->requests as $index => $item) {
            if ($item['id'] === $id) {
                $this->requests[$index]['prayed'] = true;
                $this->requests[$index]['prayers'] = $result['data']['prayers'] ?? $item['prayers'] + 1;
            }
        }
    }

    public function render()
    {
        return view('livewire.prayer-requests');
    }
}
