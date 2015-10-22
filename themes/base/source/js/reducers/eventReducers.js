/*eslint-disable */

import { handleActions } from 'redux-actions';
import objectAssign from 'object-assign';

export const events = handleActions({

  SET_SEARCH_TERM: (state, action) => {
    return {
      ...state,
      term: action.payload.term
    };
  },

  GET_EVENTS: (state, action) => {
    return {
      ...state,
      events: JSON.parse(action.payload.objects),
      count: action.payload.count,
      page: action.payload.page,
      country: action.payload.country,
      month: action.payload.month,
      eventType: action.payload.eventType,
      term: action.payload.term
    };
  },

  GET_CATEGORIES: (state, action) => {
    return {
      ...state,
      categories: JSON.parse(action.payload.objects)
    };
  },

  GET_COUNTRIES: (state, action) => {
    return {
      ...state,
      countries: JSON.parse(action.payload.objects)
    };
  },

  GET_MONTHS: (state, action) => {
    return {
      ...state,
      months: JSON.parse(action.payload.objects)
    };
  },

  GET_EVENTTYPES: (state, action) => {
    return {
      ...state,
      eventTypes: JSON.parse(action.payload.objects)
    };
  },

}, {
  countries: [],
  months: [],
  eventTypes: [],
  events: [],
  count: 0,
  term: '',
  page: 1
});
/*eslint-enable */
