<?php

namespace App\ValueObjects;

use InvalidArgumentException;

class SemanticScore
{
    private $score;

    public function __construct(float $score)
    {
        if ($score >= 0.0 && $score <= 1.0) {
            $this->score = $score;
        } else {
            throw new InvalidArgumentException('Semantic score must be between 0.0 and 1.0');
        }
    }

    public function getScore()
    {
        return $this->score;
    }

    public function compare(SemanticScore $otherScore)
    {
        return $this->score - $otherScore->getScore();
    }
}
