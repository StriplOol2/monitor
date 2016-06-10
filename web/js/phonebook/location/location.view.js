var LocationView = Backbone.View.extend({
    cities : [],
    streets : [],

    initialize: function(options) {
        _.bindAll(this);
        this.cities = options.cities.toJSON();
    },

    bindings: {
        '.city': {
            observe: 'city_id',
            initialize: function($el) {
                $el.select2({ width: 150, allowClear: true });
            },
            selectOptions: {
                collection: function() {
                    return this.cities;
                },
                valuePath: 'id',
                labelPath: 'title',
                defaultOption: {
                    label: '',
                    value: null
                }
            },
            onSet: function(val) {
                this.model.set('street_id', null);
                this.$('.street').select2('val', null);
                var city = _.find(this.cities, { id: val });

                this.model.set('street', {
                    city: {
                        id : city.id,
                        title: city.title
                    }
                });
                return val;
            }
        },
        '.street': {
            observe: ['city_id', 'street_id'],
            initialize: function($el) {
                $el.select2({ width: 150, allowClear: true });
            },
            selectOptions: {
                collection: function() {
                    var cityId = this.model.get('city_id');
                    var city = _.find(this.cities, { id: cityId });

                    this.streets = city ? city.streets : '';

                    return this.streets;
                },
                valuePath: 'id',
                labelPath: 'title',
                defaultOption: {
                    label: '',
                    value: null
                }
            },
            onSet: function(val) {
                this.model.set('street_id', val);

                var street = _.find(this.streets, { id: val });
                if (this.model.get('street') !== null) {
                    this.model.get('street').id = street.id;
                    this.model.get('street').title = street.title;
                }

                return val;
            }
        }
    },
    render: function() {
        this.stickit();
        return this;
    }
});