/*eslint-disable */

import React, {Component, PropTypes} from 'react';
import {getCountries, getMonths, getEventTypes, getEvents} from '../actions/eventActions';
import Pagination from './Pagination';
import EventFilters from './EventFilters';

export default class EventsApp extends Component {

  handlePage(page) {
    this.props.dispatch(
      getEvents(
        page,
        this.props.events.term,
        this.props.events.country,
        this.props.events.month,
        this.props.events.eventType
      )
    );
  }

  componentDidMount() {
    this.props.dispatch(getEvents(1));
    this.props.dispatch(getCountries());
    this.props.dispatch(getMonths());
    this.props.dispatch(getEventTypes());
  }

  getEvents() {
    if (this.props.events.count) {
      return this.props.events.events.map(event =>
          <article key={event.ID} className="postcard">
            <a className="postcard-left no-padding" href="{event.Link}">
              <div className="postcard-label">{event.StartTime}</div>
              <img src={event.ImageURL} alt={event.Title}/>
            </a>
            <div className="postcard-center">
              <h2><a href={event.Link}>{event.Title}</a></h2>
              <p>{event.Content}</p>
            </div>
            <div className="postcard-right">
              <div className="vcenter-parent">
                <div className="vcenter">
                  <h3 className="text-center"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 39 39" width="30px" height="30px" className="svg-map-pin-circle"><circle cx="19.6" cy="15.7" r="3.1"></circle><path d="M19.6 0C8.8 0 .1 8.7.1 19.5S8.8 39 19.6 39s19.5-8.7 19.5-19.5S30.3 0 19.6 0zM23 24.6c-1.6 3-3.2 5.4-3.2 5.5 0 .1-.1.1-.2.1s-.1 0-.2-.1c0 0-1.7-2.2-3.3-5.1-2.2-3.9-3.3-7.1-3.3-9.5 0-3.7 3-6.7 6.7-6.7s6.7 3 6.7 6.7c.1 2-1 5.1-3.2 9.1z"></path></svg> Location</h3>
                  <p className="text-center">{event.LocationAddress}</p>
                </div>
              </div>
            </div>
          </article>
      );
    } else {
      return (<p>Sorry there are no events</p>);
    }
  }

  render() {

    const events = this.getEvents();

    return (
      <div className="App">

        <EventFilters {...this.props} handlePage={this.handlePage.bind(this)} />

        {events}

        <Pagination handlePage={this.handlePage.bind(this)}
          count={this.props.events.count}
          page={this.props.events.page}
          maxLinks={7}
        />
      </div>
    );
  }

}
/*eslint-enable */
