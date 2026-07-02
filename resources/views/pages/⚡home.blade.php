<?php
use Livewire\Component;
use App\Models\DecisionSession;
use App\Models\Participant;
use Illuminate\Support\Str;

new class extends Component {
    // Create session fields
    public string $title = '';
    public string $creatorName = '';

    // Join session fields
    public string $joinCode = '';
    public string $joinName = '';

    public function createSession()
    {
        $this->validate([
            'title' => 'required|min:3',
            'creatorName' => 'required|min:2',
        ]);

        $session = DecisionSession::create([
            'code' => Str::upper(Str::random(6)),
            'title' => $this->title,
            'creator_name' => $this->creatorName,
            'status' => 'setup',
        ]);

        Participant::create([
            'session_id' => $session->id,
            'name' => $this->creatorName,
        ]);

        session(['participant_name' => $this->creatorName, 'is_creator' => true]);

        return $this->redirect(route('setup', ['code' => $session->code]));
    }

    public function joinSession()
    {
        $this->validate([
            'joinCode' => 'required',
            'joinName' => 'required|min:2',
        ]);

        $session = DecisionSession::where('code', $this->joinCode)->firstOrFail();

        $participant = Participant::create([
            'session_id' => $session->id,
            'name' => $this->joinName,
        ]);

        session(['participant_name' => $this->joinName, 'is_creator' => false]);

        return $this->redirect(route('lobby', ['code' => $session->code]));
    }
};
?>

<div>
    <h1>Group Decision Support System</h1>

    <div>
        <h2>Create a Session</h2>
        <input type="text" wire:model="title" placeholder="Session title" />
        <input type="text" wire:model="creatorName" placeholder="Your name" />
        <button wire:click="createSession">Create</button>
        @error('title') <span>{{ $message }}</span> @enderror
        @error('creatorName') <span>{{ $message }}</span> @enderror
    </div>

    <div>
        <h2>Join a Session</h2>
        <input type="text" wire:model="joinCode" placeholder="Session code" />
        <input type="text" wire:model="joinName" placeholder="Your name" />
        <button wire:click="joinSession">Join</button>
        @error('joinCode') <span>{{ $message }}</span> @enderror
        @error('joinName') <span>{{ $message }}</span> @enderror
    </div>
</div>