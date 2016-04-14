#### Simple gallery manager ####

This is a plugin to organize your gallery. We believe that displaying gallery is theme concern. You can add a gallery set with some medias.

#### Usage ####

#### Briefs ###
- Install plugin
- Go to Gallery, create some gallery sets and add some medias
- Use gallery components, set gallery sets and paging
- Voila its done

#### Components ####

This plugin provide one component. You can show your gallery using this component. Below are component properties:

- `gallery` - Specify wich gallery to use
- `pageNumber` - Specify page number
- `perPage` - Specify how many items per page (set to 0 to show all)

### Show Gallery ###

Since we are not providing default view, you must build your own display .You can get medias of gallery by accessing public method from the object returned by the components called `medias()`. It will return media object. Below are media public properties:

- `title` - Media title
- `media_url` - Media full url
- `path` - Path to storage

You can use that property to build your custom display.

### Pagination ###

Since components return an eloquent object, the pagination is the same as eloquent does.

p.s : We want to add custom title and description feature but we are confused about where to place it so it will can easily used. If you have any ideas don't hesitate to tell us via github issues.

#### Features ####
- Build media gallery as you want

#### Future Features ####
- Custom media caption and description

Todos :
- Create tests
- Listen to media delete events (still can't find out how)