#### Simple gallery manager ####

This is a plugin to organize your gallery. We believe that displaying gallery is theme concern. You can add a gallery set with some medias.

#### Usage ####

#### Briefs ###
- Install plugin
- Go to Gallery, create some gallery sets and add some medias
- Use gallery components, set gallery sets and paging
- Voila its done

#### Components ####

This plugin provide two component. Galleries is a component to show galleries and gallery is a component to show gallery conentents.

###### Galleries ######

To show galleries collection, you can use this components. The **galleries** component has three property to determine your configuration.

- `topLevelOnly` - Specify to fetch top level galleries only or all galleries.
- `pageNumber` - Specify page number
- `perPage` - Specify how many items per page (set to 0 to show all)

###### Gallery ######

- `withChild` - Specify to eager load childs relation.
- `pageNumber` - Specify page number
- `perPage` - Specify how many items per page (set to 0 to show all)

### Show Galleries ###

Since we are not providing default view, you must build your own display . You will get below properties when `accessing galleries()` method.

- `code` - Gallery code or slug
- `title` - Gallery title
- `url` - First image of gallery
- `description` - Gallery description

Example (component's alias is galleries)

    {% for key, gallery in galleries.galleries %}
    	<div class="col-lg-4 col-md-4 col-sm-12">
    	    <figure class="gallery-photo-thumb">
    	        <a href="/gallery/{{ gallery.code }}" title="{{ gallery.title }}"
    	            <img src="{{ gallery.url}}" alt="{{ gallery.title }}" />
    	        </a>
            </figure>
            <div class="gallery-photo-description">
                {{ gallery.title }}
            </div>
        </div>
     {% endfor %}

### Show Gallery ###

To show gallery content you need to get gallery by slug. You can create CMS page to wrap gallery's medias and show them. Below are gallery properties.

- `childs` - To get childs (you need to check withChild to eager load childs)
- `medias` - To get contained medias

To show childs, you can use childs properties.

Example (component's alias is gallery)

    {% for key, child in gallery.childs %}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <figure class="gallery-photo-thumb">
                <a href="/gallery/{{ child.code }}" title="{{ child.title }}">
                    <img src="{{ child.url }}" alt="{{ child.title }}" />
                </a>
            </figure>
	        <div class="gallery-photo-description">
	            {{ child.title }}
	        </div>
        </div>
     {% endfor %}

To show contained medias, you can use medias method.

Example (component's alias is gallery)

    {% for key, media in gallery.medias %}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <figure class="gallery-photo-thumb">
                <a href="{{ media.url }}" title="{{ media.title }}">
                    <img src="{{ media.url }}" alt="{{ media.title }}" />
                </a>
            </figure>
	        <div class="gallery-photo-description">
                {{ media.title }}
            </div>
        </div>
    {% endfor %}

### Pagination ###

Since components return an eloquent object, the pagination is the same as eloquent does.

#### Features ####
- Build media galleries as you want
- Nested gallery

Todos :
- Create tests
- ~~Listen to media delete events (still can't find out how)~~
- ~~Multiple medias selection~~

#### Contribution ####

You can contribute to our main repository on [github](https://github.com/inoctober/gallery).