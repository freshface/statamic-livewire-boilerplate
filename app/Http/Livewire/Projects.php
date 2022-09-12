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
    public $sort = 'title:asc';

    public $filtersToMerge = [
        'categories' => [],
        'types' => [],
        'phases' => [],
    ];

    public $orderBy = [
        'key' => 'date',
        'direaction' => 'desc'
    ];

    public $paginate = 30;

    protected $queryString = [
        'filters',
        'sort'=> ['except' => 'order:asc'],
        'search' => ['except' => '']
    ];

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    protected function entries()
    {
        $pieces = explode(":", $this->sort);
        $this->orderBy = [
            'key' => $pieces[0],
            'direction' => $pieces[1],
        ];

        $entries = Entry::query()
        ->where('collection', 'projects')
        ->where('locale', Site::current()->handle)
        ->where('status', 'published');

        $entries->when($this->filters['categories'], function ($query) {
            $query->whereTaxonomyIn($this->filters['categories']);
        });

        $entries->when($this->filters['types'], function ($query) {
            $query->whereTaxonomyIn($this->filters['types']);
        });

        $entries->when($this->filters['phases'], function ($query) {
            $query->whereTaxonomyIn($this->filters['phases']);
        });

        $entries->where('archive', '=', (bool) $this->archive);

        $entries->when($this->search, function ($query) {
            $query->where('title', 'like', '%'. $this->search . '%');
            $query->orWhere('code', 'like', '%'. $this->search . '%');
        });

        if($this->orderBy['key'] == 'order'){
            $entries->orderBy('order');
        } else {
            $entries->orderBy($this->orderBy['key'], $this->orderBy['direction']);
        }

        if($this->paginate){
            $entries = $entries->paginate($this->paginate);
            return $this->withPagination('entries', $entries);
        } else {
            return ['entries' => $entries->get()];
        }
    }


    public function render()
    {
        return view('livewire.projects', $this->readyToLoad ? $this->entries() : []);
    }

    public function mount(){
        $this->filters = array_merge($this->filtersToMerge, $this->filters);
    }

    public function clearFilterKey($key, $value = []){
        $this->filters[$key] = $value;
    }


}
