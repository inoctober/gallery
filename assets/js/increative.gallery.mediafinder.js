/*
 * MediaFinder plugin
 *
 * Data attributes:
 * - data-control="mediafinder" - enables the plugin on an element
 * - data-option="value" - an option with a value
 *
 * JavaScript API:
 * $('a#someElement').recordFinder({ option: 'value' })
 *
 * Dependences:
 * - Some other plugin (filename.js)
 */

+function ($) { "use strict";
    var Base = $.oc.foundation.base,
        BaseProto = Base.prototype

    var MediaFinder = function (element, options) {
        this.$el = $(element)
        this.$wrapper = $(element).parents('.media-wrapper')
        this.options = options || {}

        $.oc.foundation.controlUtils.markDisposable(element)
        Base.call(this)
        this.init()
    }

    MediaFinder.prototype = Object.create(BaseProto)
    MediaFinder.prototype.constructor = MediaFinder

    MediaFinder.prototype.init = function() {
        if (this.options.isMulti === null) {
            this.options.isMulti = this.$el.hasClass('is-multi')
        }

        if (this.options.isImage === null) {
            this.options.isImage = this.$el.hasClass('is-image')
        }

        this.$el.on('click', '.find-button', this.proxy(this.onClickFindButton))
        this.$el.on('click', '.find-remove-button', this.proxy(this.onClickRemoveButton))
        this.$wrapper.on('click', '.find-object', this.proxy(this.onGetMediaProperties))
        this.$el.one('dispose-control', this.proxy(this.dispose))
        this.$el.on('click', '[data-find-file-name]', this.proxy(this.onClickMediaLable));
        this.$el.on('blur', '[data-title-value]', this.proxy(this.onFocusOutMediaTitle));

        this.$findValue = $('[data-find-value]', this.$el)
        this.$titleValue = $('[data-title-value]', this.$el)
        this.$descriptionValue = $('[data-description-value]', this.$el)
    }

    MediaFinder.prototype.dispose = function() {
        this.$el.off('click', '.find-button', this.proxy(this.onClickFindButton))
        this.$el.off('click', '.find-remove-button', this.proxy(this.onClickRemoveButton))
        this.$el.off('dispose-control', this.proxy(this.dispose))
        this.$el.removeData('oc.mediaFinder')

        this.$findValue = null
        this.$el = null

        // In some cases options could contain callbacks,
        // so it's better to clean them up too.
        this.options = null

        BaseProto.dispose.call(this)
    }

    MediaFinder.prototype.onClickRemoveButton = function() {
        this.$findValue.val('')
        this.$wrapper.remove();
    }

    MediaFinder.prototype.onClickMediaLable = function() {
        $('[data-find-file-name]', this.$el).addClass('hidden');
        $('[data-title-value]', this.$el).removeClass('hidden');

        $('[data-title-value]', this.$el).focus();
    }

    MediaFinder.prototype.onFocusOutMediaTitle = function() {
        var title = $('[data-title-value]', this.$el).val();

        $('[data-find-file-name]', this.$el).text(title);
        $('[data-find-file-name]', this.$el).removeClass('hidden');
        $('[data-title-value]', this.$el).addClass('hidden');
    }

    MediaFinder.prototype.onGetMediaProperties = function() {
        var $title = this.$titleValue.val();
        var $description = this.$descriptionValue.val();

        $('input[name="media-title"]').val($title);
        $('textarea[name="media-description"]').val($description);

        $('.field-mediafinder').attr('data-media-form', 'closed');
        $('.field-mediafinder', this.$wrapper).attr('data-media-form', 'opened');
    }

    MediaFinder.prototype.onClickFindButton = function() {
        var self = this

        new $.oc.mediaManager.popup({
            alias: 'ocmediamanager',
            cropAndInsertButton: true,
            onInsert: function(items) {
                if (!items.length) {
                    alert('Please select image(s) to insert.')
                    return
                }

                var path, publicUrl

                for (var i=0, len=items.length; i<len; i++) {
                    var $clone = self.$wrapper.clone()

                    path = items[i].path
                    publicUrl = items[i].publicUrl

                    if (self.options.isImage) {
                        $('[data-find-image]', $clone).attr('src', publicUrl)
                    }

                    $clone = self.evalIsPopulated($clone, items[i])

                    $('[data-control="mediafinder"]', $clone).mediaFinder()
                    $('#media-list .media-wrapper:first').after($clone)
                }

                this.hide()
            }
        })

    }

    MediaFinder.prototype.evalIsPopulated = function($el, media) {
        var pathArray = media.path.split('/')
        var name = pathArray[pathArray.length - 1]
        
        $('.field-mediafinder', $el).addClass('is-populated')
        $('[data-find-file-name]', $el).text(name)
        $('[data-title-value]', $el).val(name)
        $('[data-find-value]', $el).val(name)
        $('[data-find-folder]', $el).val(media.folder)

        return $el;
    }

    MediaFinder.DEFAULTS = {
        isMulti: null,
        isImage: null
    }

    // PLUGIN DEFINITION
    // ============================

    var old = $.fn.mediaFinder

    $.fn.mediaFinder = function (option) {
        var args = arguments;

        return this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.mediaFinder')
            var options = $.extend({}, MediaFinder.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.mediaFinder', (data = new MediaFinder(this, options)))
            if (typeof option == 'string') data[option].apply(data, args)
        })
      }

    $.fn.mediaFinder.Constructor = MediaFinder

    $.fn.mediaFinder.noConflict = function () {
        $.fn.mediaFinder = old
        return this
    }

    $(document).render(function (){
        $('[data-control="mediafinder"]').mediaFinder()
        $('#save-media-form').on('click', function(e){
          e.preventDefault();
          var $form = $(this).parents('#media-form');

          var title = $('input[name="media-title"]', $form).val();
          var description = $('textarea[name="media-description"]', $form).val();

          $('[data-media-form="opened"] input[name="medias_title[]"]').val(title);
          $('[data-media-form="opened"] input[name="medias_description[]"]').val(description);
          $('[data-media-form="opened"] .media-title').text(title);

          return false;
        });
    })

}(window.jQuery);
