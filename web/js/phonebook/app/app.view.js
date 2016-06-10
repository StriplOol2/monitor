var AppView = Backbone.View.extend({

    el: $(".phonebook"),
    elTable: $(".phonebook-table"),
    elLoader: $('#initial-loader'),
    elAddRecordButton: $('.add-record-button'),

    events: {
        "click .add-record-button" : "addEmptyRecord"
    },

    initialize: function(options) {
        this.cityCollection = options.cityCollection;
        this.bindings();
        this.fetchCollection(this.cityCollection, {async: false}, 'Не смог загрузить города :(');
        this.fetchCollection(this.collection, {async: false}, 'Не смог загрузить записи справочника :(');
        var self = this;
        setInterval(function() {
            self.fetchCollection(self.collection, {async: true});
        }, 10000);

        this.elLoader.hide();
    },

    bindings: function() {
        this.collection.bind('add', this.addOneRecord, this);
        this.collection.bind('reset', this.addAllRecords, this);
        this.collection.bind('all', this.render, this);
    },

    fetchCollection: function(collection, options, errorMessage) {
        //@todo сделать нормальную обработку ошибок
        collection.fetch(options).success(function(result){
            if (result.error){
                alert(errorMessage);
            }
        }).error(function(){
            alert(errorMessage);
        });
    },

    addOneRecord: function(record) {
        var view = new RecordView({model: record, cityCollection: this.cityCollection});
        this.elTable.append(view.render().el);
    },

    addEmptyRecord: function() {
        this.elLoader.show();
        this.elAddRecordButton.hide();
        this.collection.create(new Record(), {async: false});
        this.fetchCollection(this.collection, {async: false});
        var lastModel = this.collection.at(this.collection.length - 1);
        lastModel.trigger('record_edit');
        this.elLoader.hide();
        this.elAddRecordButton.show();
    },

    addAllRecords: function() {
        this.collection.each(this.addOneRecord);
    }
});
