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
        init: function(options) {
            var self = this;

            self.options = $.extend({}, $.robojax.options, options);
            self.$modal = $('div#tagModal');
            self.$modalBody = self.$modal.children('.modal-body');
            self.ready = true;


            self.listen();
        },

        listen: function()
        {
            var self = this;

            $('body').on('click', 'a.robojax', function(e) {
                e.preventDefault();
                if (self.ready === false) {
                    return;
                }
                self.ready = false;
                self.execute($(this));
            });

            $('body').on('click', 'a.robojax_submit', function(e) {
                e.preventDefault();
                if (self.ready === false) {
                    return;
                }
                self.ready = false;
                self.submitForm($(this));
            });
        },

        execute: function($this)
        {
            var self = this;

            if ($this.hasClass('delete')) {
                self.delete($this);
                return;
            }

            self.$modalBody
                .empty()
                .html('<div class="text-center"><img style="padding: 40px;" src="/bundles/msicmf/img/ajax-loader3.gif" alt="ajax-loader"></div>')
            ;

            $('div#tagModal').modal('show');

            $.ajax($this.attr('href'), {
                success: function (data) {
                    self.ready = true;

                    self.$modalBody
                        .html($(data).find('form.form-crud'))
                    ;
                }
            });
        },

        delete: function($this)
        {
            var self = this;

            $('#loader').removeClass('hide');

            $.ajax($this.attr('href'), {
                success: function (data) {
                    self.ready = true;

                    self.options.success(data);
                }
            });
        },

        submitForm: function($this)
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
                    self.ready = true;

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

    $.robojax = function(options) {
        var robojax = Object.create(Robojax);
        robojax.init(options);
    };

    $.robojax.options = {
        success: function() {}
    };
})(jQuery, window);
