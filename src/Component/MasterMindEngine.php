<?php

namespace App\Component;

use App\Enum\GameStatusEnum;

class MasterMindEngine
{
    protected const MAX_COLORS = 6;
    protected const MAX_CASE = 4;
    protected const ATTEMPT_MAX = 12;
    protected array $game = [];
    protected array $played = [];
    protected int $numberOfPlay;
    protected int $wellPlaced;
    protected int $noneWellPlaced;
    protected GameStatusEnum $gameStatus;

    public function newGame(): void
    {
        $this->numberOfPlay = 0;
        $this->wellPlaced = 0;
        $this->noneWellPlaced = 0;

        for ($i = 0 ; $i < self::MAX_CASE ; $i++){
            $this->game[$i] = rand(1, self::MAX_COLORS);
        }

        $this->played = [];
        $this->gameStatus = GameStatusEnum::IN_PROGRESS;
    }

    public function getGame(): array
    {
        return $this->game;
    }

    public function play(array $colors): bool
    {
        $this->wellPlaced = 0;
        $this->noneWellPlaced = 0;
        $colors = $this->toIntArray($colors);
        $this->numberOfPlay++;
        if ($this->equality($this->game , $colors)){
            $this->wellPlaced = count($colors);
            $this->noneWellPlaced = 0;
            $this->hasPlayed($colors);
            $this->gameStatus = GameStatusEnum::HAS_WON;
            return true;
        }
        $this->calcNumberOfWellPlaced($this->game , $colors);
        $this->hasPlayed($colors);
        if ($this->numberOfPlay >= self::ATTEMPT_MAX){
            $this->gameStatus = GameStatusEnum::HAS_LOOSED;
        }
        return false;
    }

    public function isAttemptExceeded() : bool
    {
        return $this->numberOfPlay >= self::ATTEMPT_MAX;
    }

    private function equality(array $game, array $colors): bool
    {
        for ($i = 0 ; $i < self::MAX_CASE ; $i++){
            if ($game[$i] !== $colors[$i]){
                return false;
            }
        }

        return true;
    }

    private function toIntArray(array $colors): array
    {
        return array_map(fn ($color) => (int)$color, $colors);
    }

    private function calcNumberOfWellPlaced(array $game, array $colors): void
    {
        $this->wellPlaced = 0;

        for ($i = 0 ; $i < self::MAX_CASE ; $i++){
            if ($game[$i] === $colors[$i]){
                $this->wellPlaced++;
                unset($game[$i]);
                unset($colors[$i]);
            }
        }
        $this->noneWellPlaced = 0;
        $this->calcNumberOfNoneWellPlaced(array_values($game), array_values($colors));
    }

    /**
     * @return int
     */
    public function getNumberOfPlay(): int
    {
        return $this->numberOfPlay;
    }

    /**
     * @return int
     */
    public function getWellPlaced(): int
    {
        return $this->wellPlaced;
    }

    /**
     * @return int
     */
    public function getNoneWellPlaced(): int
    {
        return $this->noneWellPlaced;
    }

    private function calcNumberOfNoneWellPlaced(array $game, array $colors): void
    {
        $continue = false;
        for ($i = 0 ; $i < count($game) ; $i++){
            for ($j = 0 ; $j < count($colors) ; $j++){
                if ($game[$i] === $colors[$j]){
                    $this->noneWellPlaced++;
                    unset($game[$i]);
                    unset($colors[$j]);
                    $continue = true;
                    break 2;
                }
            }
        }
        if ($continue){
            $this->calcNumberOfNoneWellPlaced(array_values($game), array_values($colors));
        }
    }

    /**
     * @return array
     */
    public function getPlayed(): array
    {
        return $this->played;
    }

    private function hasPlayed(array $colors): void
    {
        $this->played[] = [
            'colors' => $colors,
            'wellPlaced' => $this->wellPlaced,
            'noneWellPlaced' => $this->noneWellPlaced
        ];
    }

    public function getGameStatus(): GameStatusEnum
    {
        return $this->gameStatus;
    }

}
