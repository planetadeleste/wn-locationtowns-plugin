<?php namespace PlanetaDelEste\LocationTown\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Winter\Location\Models\State;
use PlanetaDelEste\LocationTown\Models\Town as TownModel;

class Towns extends ComponentBase
{
    private $towns;

    private $townPage;

    public function componentDetails()
    {
        return [
            'name' => 'planetadeleste.locationtown::lang.towns.title',
            'description' => 'planetadeleste.locationtown::lang.towns.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title' => 'planetadeleste.locationtown::lang.towns.pagination',
                'description' => 'planetadeleste.locationtown::lang.towns.pagination_description',
                'type' => 'string',
                'default' => '{{ :page }}',
            ],
            'stateFilter' => [
                'title' => 'planetadeleste.locationtown::lang.towns.state',
                'description' => 'planetadeleste.locationtown::lang.towns.state_description',
                'type' => 'dropdown',
            ],
            'townsPerPage' => [
                'title' => 'planetadeleste.locationtown::lang.towns.per_page',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'planetadeleste.locationtown::lang.towns.per_page_validation',
                'default' => '10',
            ],
            'noTownsMessage' => [
                'title' => 'planetadeleste.locationtown::lang.towns.no_towns',
                'description'  => 'planetadeleste.locationtown::lang.towns.no_towns_description',
                'type' => 'string',
                'default' => 'No towns found',
                'showExternalParam' => false,
            ],
            'townPage' => [
                'title' => 'planetadeleste.locationtown::lang.towns.page',
                'description' => 'planetadeleste.locationtown::lang.towns.page_description',
                'type' => 'dropdown',
                'default' => 'town/detail',
            ],
        ];
    }

    public function onRun()
    {
        // init params
        $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->page['noTownsMessage'] = $this->property('noTownsMessage');
        $this->townPage = $this->page['townPage'] = $this->property('townPage');

        // load towns
        $this->towns = $this->page['towns'] = $this->listTowns();

        // if the page number is not valid, redirect
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->towns->lastPage()) && $currentPage > 1) {
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
            }
        }
    }

    protected function listTowns()
    {
        $towns = TownModel::isEnabled()->paginate($this->property('postsPerPage'), $this->property('pageNumber'));

        // set town URL depends on configured town page parameter
        $towns->each(function($post) {
            $post->setUrl($this->townPage, $this->controller);
        });

        return $towns;
    }

    public function getTownPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getStateFilterOptions()
    {
        return State::orderBy('name')->lists('name', 'id');
    }
}