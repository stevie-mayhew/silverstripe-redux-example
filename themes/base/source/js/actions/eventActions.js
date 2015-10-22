/*eslint-disable */

import { createAction } from 'redux-actions';
import request from 'superagent';

function ajax(method, url, data = null) {
  return new Promise(function (resolve, reject) {
    var req = request;
    method = method.toUpperCase();

    if (method === 'POST') {
      req = req.post(url).send(data);
    } else {
      req = req.get(url);
    }

    req.end(function (err, res) {
      if (res.ok) {
        resolve(res.text);
      } else {
        reject(res.text);
      }
    });
  });
}

export const setSearchTerm = createAction('SET_SEARCH_TERM', (term) => {
  return {term: term}
});

export const getEvents = createAction('GET_EVENTS', (page = 1, keyword = '', countryId = '', monthStr = '', eventId = '') => {
  const keywordQuery = keyword !== '' ? `&keywords=${keyword}` : '';
  const countryQuery = countryId !== '' ? `&country=${countryId}` : '';
  const monthQuery = monthStr !== '' ? `&month=${monthStr}` : '';
  const eventTypeQuery = eventId !== '' ? `&eventType=${eventId}` : '';
  return ajax('GET', `api/events/?page=${page}${keywordQuery}${countryQuery}${monthQuery}${eventTypeQuery}`)
    .then(JSON.parse)
    .then((data) => ({...data, page, term: keyword, country: countryId, month: monthStr, eventType: eventId}));
});

export const getCountries = createAction('GET_COUNTRIES', () => {
  return ajax('GET', `api/countries`)
    .then(JSON.parse)
    .then((data) => ({...data}));
});

export const getMonths = createAction('GET_MONTHS', () => {
  return ajax('GET', `api/months`)
    .then(JSON.parse)
    .then((data) => ({...data}));
});

export const getEventTypes = createAction('GET_EVENTTYPES', () => {
  return ajax('GET', `api/eventTypes`)
    .then(JSON.parse)
    .then((data) => ({...data}));
});

/*eslint-enable */
