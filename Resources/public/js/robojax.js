if ( typeof Object.create !== 'function' ) {
    Object.create = function( obj ) {
        function F() {}
        F.prototype = obj;
        return new F();
    };
}

(function($, window, undefined) {
    "use strict";

    var Robojax = {
        init: function(el, options) {
            var self = this;

            self.$el = $(el);
            self.options = $.extend({}, $.fn.robojax.options, options);
            self.$modal = $('div#tagModal');
            self.$modalBody = self.$modal.children('.modal-body');


            self.listen();
        },

        listen: function()
        {
            var self = this;

            self.$el.on('click', function(e) {
                e.preventDefault();
                self.new($(this));
            });

            $('a.robojax_submit').on('click', function(e) {
                e.preventDefault();
                self.create($(this));
            });
        },

        new: function($this)
        {
            var self = this;

            self.$modalBody
                .empty()
                .html('<div class="text-center"><img style="padding: 40px;" src="/bundles/msicmf/img/ajax-loader3.gif" alt="ajax-loader"></div>')
            ;

            $('div#tagModal').modal('show');

            $.ajax($this.attr('href'), {
                success: function (data) {
                    self.$modalBody
                        .html($(data).find('form.form-crud'))
                    ;
                }
            });
        },

        create: function($this)
        {
            var self = this;

            var $form = $('div#tagModal div.modal-body form');

            $form
                .css('visibility', 'hidden');

            self.$modalBody
                .css('background', 'url(/bundles/msicmf/img/ajax-loader3.gif) no-repeat center center #fff');

            $.ajax($form.attr('action'), {
                type: 'POST',
                data: $form.serialize(),
                success: function (data) {
                    self.$modalBody
                        .css('background', '#fff');
                    if (data.entity) {
                        $('div#tagModal').modal('hide');
                        self.options.success(data);
                    } else {
                        $('div#tagModal div.modal-body').html($(data).find('form.form-crud'));
                    }
                }
            });
        }
    };

    $.fn.robojax = function(options) {
        return this.each(function() {
            var robojax = Object.create(Robojax);
            robojax.init(this, options);
        });
    };

    $.fn.robojax.options = {
        success: function() {}
    };
})(jQuery, window);
