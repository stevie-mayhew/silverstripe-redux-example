require('babel/polyfill');

/*eslint-disable */
import objectAssign from 'object-assign';
import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import { Provider, connect } from 'react-redux';
import promiseMiddleware from 'redux-promise';
import { createStore, applyMiddleware } from 'redux';
import EventsApp from './components/EventsApp';
import {reducers} from './reducers';
/*eslint-enable */

const createStoreWithMiddleware = applyMiddleware(promiseMiddleware)(createStore);
const store = createStoreWithMiddleware(reducers);
const AppConnectorEvents = connect(state => (objectAssign({}, state)))(EventsApp);

document.addEventListener('DOMContentLoaded', function init() {
  // Events store
  if (document.getElementById('Events')) {
    ReactDOM.render(
      <Provider store={store}>
        {() => <AppConnectorEvents store={store}/>}
      </Provider>,
      document.getElementById('Events')
    );
  }
});
