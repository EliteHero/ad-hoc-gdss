<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\DecisionSession;
use App\Models\Criterion;
use App\Models\Comparison;

new class extends Component
{
    public string $code = '';
    public int $sessionId;
    public int $sliderValue = 0;

    public int $participantId;

    public function mount($code)
    {
        $this->code = $code;
        $session = DecisionSession::where('code', $code)->firstOrFail();
        $this->sessionId = $session->id;
        $this->participantId = session('participant_id');
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
    public function pairs()
    {
        $criteria = $this->criteria;
        $pairs = [];

        foreach ($criteria as $i => $a) {
            foreach ($criteria as $j => $b) {
                if ($j > $i) {
                    $pairs[] = [$a, $b];
                }
            }
        }

        return $pairs;
    }

    #[Computed]
    public function currentIndex()
    {
        return Comparison::where('participant_id', $this->participantId)->count();
    }

    #[Computed]
    public function currentPair()
    {
        return $this->pairs[$this->currentIndex] ?? null;
    }

    public function submitComparison($value)
    {
        $criterionA = $this->currentPair[0]->id;
        $criterionB = $this->currentPair[1]->id;
        $absValue = abs($value);

        // if negative, B is more important → swap
        if ($value < 0) {
            [$criterionA, $criterionB] = [$criterionB, $criterionA];
        }

        Comparison::create([
            'session_id' => $this->sessionId,
            'participant_id' => $this->participantId,
            'criterion_a_id' => $criterionA,
            'criterion_b_id' => $criterionB,
            'value' => $absValue
        ]);

        if ($this->currentIndex >= count($this->pairs)) {
            Participant::find($this->participantId)
                ->update(['has_submitted_comparisons' => true]);
        }

        unset($this->pairs);
        unset($this->currentIndex);
        unset($this->currentPair);
        $this->sliderValue = 0;
    }
};
?>

<div>
    <h1>Pairwise Comparison</h1>
    <p>Session: {{ $this->session->title }}</p>
    <p>Progress: {{ $this->currentIndex }} / {{ count($this->pairs) }}</p>

    @if($this->currentPair)
        <div>
            <h2>Which criterion is more important?</h2>

            <div>
                <p><strong>{{ $this->currentPair[0]->name }}</strong></p>
                <p>vs</p>
                <p><strong>{{ $this->currentPair[1]->name }}</strong></p>
            </div>

            <div>
                @foreach([
                    9 => $this->currentPair[0]->name . ' much more important',
                    5 => $this->currentPair[0]->name . ' more important',
                    1 => 'Equally important',
                    -5 => $this->currentPair[1]->name . ' more important',
                    -9 => $this->currentPair[1]->name . ' much more important',
                ] as $val => $label)
                    <label>
                        <input
                            type="radio"
                            name="comparison"
                            value="{{ $val }}"
                            wire:model.live="sliderValue"
                        />
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <button
                wire:click="submitComparison({{ $sliderValue }})"
                wire:loading.attr="disabled"
                @if(!$sliderValue) disabled @endif
            >
                Next →
            </button>
        </div>
    @else
        <p>All comparisons done! Waiting for other participants...</p>
    @endif
</div>