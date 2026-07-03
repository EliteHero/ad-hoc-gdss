<?php
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\DecisionSession;
use App\Models\Criterion;
use App\Models\Alternative;
use App\Models\Participant;

new class extends Component {
    public string $code = '';
    public int $sessionId;
    
    // Criteria fields
    public string $criterionName = '';
    public string $criterionType = 'benefit';
    public bool $isFixed = false;
    public $fixedValue = null;

    // Alternative fields
    public string $alternativeName = '';

    public function mount($code)
    {
        $this->code = $code;
        $session = DecisionSession::where('code', $code)->firstOrFail();
        $this->sessionId = $session->id;
    }

    #[Computed]
    public function session()
    {
        return DecisionSession::find($this->sessionId);
    }

    #[Computed]
    public function criteria()
    {
        return Criterion::where('session_id', $this->sessionId)->get();
    }

    #[Computed]
    public function alternatives()
    {
        return Alternative::where('session_id', $this->sessionId)->get();
    }

    #[Computed]
    public function participants()
    {
        return Participant::where('session_id', $this->sessionId)->get();
    }

    public function addCriterion()
    {
        $this->validate([
            'criterionName' => 'required|min:2',
            'fixedValue' => $this->isFixed ? 'required|numeric' : 'nullable',
        ]);

        Criterion::create([
            'session_id' => $this->sessionId,
            'name' => $this->criterionName,
            'type' => $this->criterionType,
            'is_fixed' => $this->isFixed,
            'fixed_value' => $this->isFixed ? $this->fixedValue : null,
        ]);

        $this->criterionName = '';
        $this->criterionType = 'benefit';
        $this->isFixed = false;
        $this->fixedValue = null;
    }

    public function addAlternative()
    {
        $this->validate([
            'alternativeName' => 'required|min:2',
        ]);

        Alternative::create([
            'session_id' => $this->sessionId,
            'name' => $this->alternativeName,
        ]);

        $this->alternativeName = '';
    }

    public function deleteCriterion($id)
    {
        Criterion::find($id)->delete();
    }

    public function deleteAlternative($id)
    {
        Alternative::find($id)->delete();
    }

    public function startWeighting()
    {
        $this->session->update(['status' => 'weighting']);
        return $this->redirect(route('weight', ['code' => $this->code]));
    }
};
?>

<div>
    <h1>Lobby: {{ $this->session->title }}</h1>

    @if (session('is_creator'))
        {{-- Criteria Section --}}
        <div>
            <h2>Criteria</h2>
            <input type="text" wire:model="criterionName" placeholder="Criterion name" />
            <select wire:model="criterionType">
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
            </select>
            <label>
                <input type="checkbox" wire:model.live="isFixed" /> Fixed value?
            </label>
            @if($isFixed)
                <input type="number" wire:model="fixedValue" placeholder="Fixed value" />
            @endif
            <button wire:click="addCriterion" wire:loading.attr="disabled">Add Criterion</button>
            @error('criterionName') <span>{{ $message }}</span> @enderror
            @error('fixedValue') <span>{{ $message }}</span> @enderror

            <ul>
                @foreach($this->criteria as $criterion)
                    <li>
                        {{ $criterion->name }} ({{ $criterion->type }})
                        @if($criterion->is_fixed) — Fixed: {{ $criterion->fixed_value }} @endif
                        <button wire:click="deleteCriterion({{ $criterion->id }})">Delete</button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Alternatives Section --}}
        <div>
            <h2>Alternatives</h2>
            <input type="text" wire:model="alternativeName" placeholder="Alternative name" />
            <button wire:click="addAlternative" wire:loading.attr="disabled">Add Alternative</button>
            @error('alternativeName') <span>{{ $message }}</span> @enderror

            <ul>
                @foreach($this->alternatives as $alternative)
                    <li>
                        {{ $alternative->name }}
                        <button wire:click="deleteAlternative({{ $alternative->id }})">Delete</button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Start Weighting --}}
        <div>
            <button wire:click="startWeighting" wire:loading.attr="disabled">
                Start Weighting
            </button>
        </div>
    @else
        <p>Waiting for the creator to start the session...</p>
    @endif

    <div wire:poll.3s>
        <h2>Participants</h2>
        <ul>
            @foreach ($this->participants as $participant)
                <li>
                    {{ $participant->name }}
                </li>
            @endforeach
        </ul>
    </div>
</div>