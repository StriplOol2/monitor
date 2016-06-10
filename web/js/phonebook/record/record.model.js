var Record = Backbone.Model.extend({
    defaults: {
        birth_timestamp: 0,
        street: null,
        name: '',
        surname: '',
        patronymic: '',
        phone_number: ''
    },

    clear: function() {
        this.destroy();
    },

    sync: function (method, model, options) {
        if (method == 'update' || method == 'create') {
            model.set("record_request", {
                "name":model.get('name'),
                "surname":model.get('surname'),
                "patronymic":model.get('patronymic'),
                "birth_timestamp":model.get('birth_timestamp'),
                "phone_number":model.get('phone_number'),
                "street_id": model.get('street_id')
            });
        }

        return Backbone.sync.call(this, method, model, options);
    }
});
