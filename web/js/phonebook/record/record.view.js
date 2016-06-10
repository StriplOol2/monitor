var RecordView = Backbone.View.extend({
    tagName:  "tr",
    className: "record",
    cityCollection: {},
    template: _.template($('script#record-template').html()),
    locationView: null,
    events: {
        "click .destroy" : "clear",
        "click .edit-button"  : "edit",
        "click .save-button" : "save"
    },

    initialize: function(options) {
        this.cityCollection = options.cityCollection;

        this.locationView = new LocationView({
            model: this.model,
            el: this.el,
            cities: this.cityCollection
        });

        this.model.on('saved', this.render, this);
        this.model.on('record_edit', this.edit, this);
        this.model.on('remove', this.clearView, this);
    },

    render: function() {
        this.$el.html(this.template(this.model.toJSON()));
        this.locationView.render();
        return this;
    },

    clear: function() {
        this.model.clear();
    },

    clearView: function() {
        this.$el.html('');
    },

    edit: function() {
        this.model.set('street', null);
        this.model.set('street_id', null);
        this.$el.addClass("editing");
    },

    save: function() {
        this.$el.removeClass("editing");
        var model = {};
        this.$el.find('.edit').each(function() {
            //не красиво :( но что поделаешь пол первого ночи, а завтра на работу :)
            if ('birth_date_formated' == $(this).attr("name")) {
                var myDate= $(this).val();
                myDate=myDate.split("/");
                var newDate=myDate[1]+"/"+myDate[0]+"/"+myDate[2];
                model['birth_timestamp'] = new Date(newDate).getTime()/1000;
            } else {
                model[$(this).attr("name")] = $(this).val();
            }
        });
        this.model.save(model);
        this.model.trigger('saved');
    }
});
