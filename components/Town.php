<?php namespace PlanetaDelEste\LocationTown\Components;

use Cms\Classes\ComponentBase;
use PlanetaDelEste\LocationTown\Models\Town as TownModel;

class Town extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'planetadeleste.locationtown::lang.town.title',
            'description' => 'planetadeleste.locationtown::lang.town.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'planetadeleste.locationtown::lang.town.slug',
                'description' => 'planetadeleste.locationtown::lang.town.slug_description',
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->page['town'] = $this->loadTown();
    }

    protected function loadTown()
    {
        $slug = $this->property('slug');

        return TownModel::where('slug', $slug)->isEnabled()->first();
    }
}