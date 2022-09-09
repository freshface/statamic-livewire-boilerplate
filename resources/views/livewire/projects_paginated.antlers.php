<div wire:init="loadPosts">

    <h3 class="font-bold">Categorieen</h3>
    <ul>
        {{ if filters.categories }}
            <li>
                <a wire:click="clearFilterKey('categories')">Clear filter</a>
            </li>
        {{ /if }}
        {{ taxonomy from="categories" }}
            <li>
                <label for="categories_{{ slug }}"><input type="checkbox" name="categories" value="categories::{{ slug }}" id="categories_{{ slug }}" wire:model="filters.categories"> {{ title }}</label>
            </li>
        {{ /taxonomy }}
    </ul>

    <h3 class="font-bold">Fases</h3>
    <ul>
        {{ if filters.phases }}
            <li>
                <a wire:click="clearFilterKey('phases')">Clear filter</a>
            </li>
        {{ /if }}
        {{ taxonomy from="phases" }}
            <li>
                <label for="phases_{{ slug }}"><input type="checkbox" name="phases" value="phases::{{ slug }}" id="phases_{{ slug }}" wire:model="filters.phases"> {{ title }}</label>
            </li>
        {{ /taxonomy }}
    </ul>

    <h3 class="font-bold">Types</h3>
    <ul>
        {{ if filters.types }}
            <li>
                <a wire:click="clearFilterKey('types')">Clear filter</a>
            </li>
        {{ /if }}
        {{ taxonomy from="types" }}
            <li>
                <label for="types_{{ slug }}"><input type="checkbox" name="types" value="types::{{ slug }}" id="types_{{ slug }}" wire:model="filters.types"> {{ title }}</label>
            </li>
        {{ /taxonomy }}
    </ul>

    <input type="text" wire:model="search" placeholder="Search...">

    {{ if entries }}
        <ul>
        {{ entries }}
            <li>{{ title }} {{ code }}</li>
    {{ /entries }}
        </ul>

    {{ else }}
        No results
    {{ /if }}

    {{ links }}

</div>
