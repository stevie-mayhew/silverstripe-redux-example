/*eslint-disable */

import React, {Component} from 'react';
import {getCountries, getMonths, getEventTypes, getEvents, setSearchTerm} from '../actions/eventActions';
import SearchFilter from './filters/SearchFilter';
import DropdownFilter from './filters/DropdownFilter';
import ResetFilters from './filters/ResetFilters';

export default class EventFilters extends Component {

  handleCountryChange(event) {
    this.props.dispatch(
      getEvents(
        1,
        this.props.events.term,
        event.target.value,
        this.props.events.month,
        this.props.events.eventType
      )
    );
  }

  handleMonthChange(event) {
    this.props.dispatch(
      getEvents(
        1,
        this.props.events.term,
        this.props.events.country,
        event.target.value,
        this.props.events.eventType
      )
    );
  }

  handleEventTypeChange(event) {
    this.props.dispatch(
      getEvents(
        1,
        this.props.events.term,
        this.props.events.country,
        this.props.events.month,
        event.target.value
      )
    );
  }

  clearFilters() {
    this.props.dispatch(
      getEvents(
        1,
        '',
        '',
        '',
        ''
      )
    );
  }

  render() {
    return (
      <div className="filter-set">
        <div className="row rp5">

          <div className="col-xs-3 cp5">
            <SearchFilter
              label="Search..."
              {...this.props}
              handlePage={this.props.handlePage.bind(this, 1)}
              />
          </div>

          <div className="col-xs-1 cp5">
            <label>Filter:</label>
          </div>

          <div className="col-xs-2 cp5">
            <DropdownFilter
              label="All Dates"
              {...this.props}
              value={this.props.events.month}
              change={this.handleMonthChange.bind(this)}
              options={this.props.events.months}
              />
          </div>

          <div className="col-xs-2 cp5">
            <DropdownFilter
              label="All Countries"
              {...this.props}
              value={this.props.events.country}
              change={this.handleCountryChange.bind(this)}
              options={this.props.events.countries}
              />
          </div>

          <div className="col-xs-2 cp5">
            <DropdownFilter
              label="All Types"
              {...this.props}
              value={this.props.events.eventType}
              change={this.handleEventTypeChange.bind(this)}
              options={this.props.events.eventTypes}
              />
          </div>

          <div className="col-xs-2 cp5">
            <ResetFilters clearFilters={this.clearFilters.bind(this)} />
          </div>
        </div>
      </div>
    );
  }
}

/*eslint-enable */
