import { View } from 'backbone.marionette';
import { Model } from 'backbone';
import _, { template } from 'underscore';
import $ from 'jquery';

_.templateSettings = {
  interpolate: /\{\{(.+?)\}\}/g,
};
const apiUrl = 'http://localhost:8000/api/';

const CounterView = View.extend({
  modelEvents: {
    change: 'render',
  },

  template: template('<span id="total-counter">{{total}}</span> &euro; Api title: {{response}}'),

  regions: {
    firstRegion: '#first-region',
  },
});

const CounterModel = new Model({ total: 1000, response: 'Loading...' });

const MyView = View.extend({
  template: template('<div id="first-region">Loading...</div>'),

  regions: {
    firstRegion: '#first-region',
  },

  async loadApiData() {
    try {
      const response = await fetch(apiUrl);
      const decoded = await response.json();
      this.model.set('response', decoded.title);
    } catch (e) {
      this.model.set('response', `Error! ${e}`);
    }
  },

  onRender() {
    this.showChildView('firstRegion', new CounterView({ model: this.model }));
    this.loadApiData();
  },
});

const myView = new MyView({ model: CounterModel });
myView.render();

$('body').append(myView.$el);
