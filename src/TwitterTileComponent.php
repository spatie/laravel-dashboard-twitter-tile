<?php

namespace Spatie\TwitterTile;

use Livewire\Component;

class TwitterTileComponent extends Component
{
    /** @var string */
    public $position;

    /** @var string */
    public $configurationName;

    public function mount(string $position, string $configurationName = 'default')
    {
        $this->position = $position;

        $this->configurationName = $configurationName;
    }

    public function render()
    {
        return view('components.tiles.twitter', [
            'tweets' => TwitterStore::make($this->configurationName)->tweets(),
        ]);
    }
}
