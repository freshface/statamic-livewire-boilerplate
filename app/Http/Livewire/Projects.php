<?php

namespace App\Http\Livewire;
use Statamic\Facades\Entry;
use Livewire\Component;
use Statamic\Facades\Site;
use Jonassiewertsen\Livewire\WithPagination;

class Projects extends Component
{

    use WithPagination;

    public $readyToLoad = false;

     /* Wire models */
    public $filters = [];
    public $search = '';
    public $archive = false;

    public $filtersToMerge = [
        'categories' => [],
        'types' => [],
        'phases' => [],
    ];

    public $orderBY = [
        'key' => 'date',
        'direaction' => 'desc'
    ];

    public $paginate = 30;

    protected $queryString = [
        'filters',
        'search' => ['except' => '']
    ];

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    protected function entries()
    {
        $entries = Entry::query();
        $entries->where('collection', 'projects');
        $entries->where('locale', Site::current()->handle);
        $entries->where('status', 'published');

        $entries->when($this->filters['categories'], function ($query) {
            $query->whereTaxonomyIn($this->filters['categories']);
        });

        $entries->when($this->filters['types'], function ($query) {
            $query->whereTaxonomyIn($this->filters['types']);
        });

        $entries->when($this->filters['phases'], function ($query) {
            $query->whereTaxonomyIn($this->filters['phases']);
        });

        $entries->where('archiveer', '=', $this->archive);

        $entries->when($this->search, function ($query) {
            $query->where('title', 'like', '%'. $this->search . '%');
            $query->orWhere('code', 'like', '%'. $this->search . '%');
        });

        $entries->orderBy('order');

        $entries = $entries->paginate($this->paginate);

        return $this->withPagination('entries', $entries);
    }


    public function render()
    {
        return view('livewire.projects_paginated', $this->readyToLoad ? $this->entries() : []);
    }

    public function mount(){
        $this->filters = array_merge($this->filtersToMerge, $this->filters);
        $this->archive = (bool) $this->archive;
    }

    public function clearFilterKey($key, $value = []){
        $this->filters[$key] = $value;
    }


}
