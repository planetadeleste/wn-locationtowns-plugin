<?php namespace PlanetaDelEste\LocationTown;

use Backend;
use Event;
use Winter\Location\Models\State;
use System\Classes\PluginBase;
use PlanetaDelEste\LocationTown\Models\Town;

/**
 * LocationTown Plugin Information File
 */
class Plugin extends PluginBase
{
    /** @var array $require Required plugins */
    public $require = [
        'Winter.Location'
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'planetadeleste.locationtown::lang.plugin.name',
            'description' => 'planetadeleste.locationtown::lang.plugin.description',
            'author' => 'Alvaro Canepa',
            'icon' => 'icon-building-o',
            'replaces'    => ['VojtaSvoboda.Location' => '<= 1.0.7'],
        ];
    }

    public function registerComponents()
    {
        return [
            'PlanetaDelEste\LocationTown\Components\Town' => 'locationTown',
            'PlanetaDelEste\LocationTown\Components\Towns' => 'locationTowns',
        ];
    }

    public function registerSettings()
    {
        return [
            'locationtown' => [
                'label' => 'planetadeleste.locationtown::lang.settings.label',
                'description' => 'planetadeleste.locationtown::lang.settings.description',
                'category' => 'winter.location::lang.plugin.name',
                'icon' => 'icon-building-o',
                'url' => Backend::url('planetadeleste/locationtown/towns'),
                'order' => 500,
                'permissions' => ['planetadeleste.locationtown.*'],
            ],
        ];
    }

    /**
     * Register new Twig variables
     * @return array
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'form_select_town' => ['PlanetaDelEste\LocationTown\Models\Town', 'formSelect'],
            ]
        ];
    }

    public function boot()
    {
        $this->app->bind('locationtowns', 'PlanetaDelEste\LocationTown\Models\Town');

        State::extend(function($model) {
            $model->hasMany['towns'] = 'PlanetaDelEste\LocationTown\Models\Town';
        });

        $this->initMenuItems();
    }

    /**
     * Register new MenuItems, which is usefull for Sitemap and etc.
     */
    public function initMenuItems()
    {
        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'location-town' => 'Location Town',
                'all-location-towns' => 'All location towns'
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function($type) {
            if ($type == 'location-town' || $type == 'all-location-towns') {
                return Town::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            if ($type == 'location-town' || $type == 'all-location-towns') {
                return Town::resolveMenuItem($item, $url, $theme);
            }
        });
    }
}
