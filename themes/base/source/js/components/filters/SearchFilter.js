/*eslint-disable */

import React, {Component, PropTypes} from 'react';
import {setSearchTerm} from '../../actions/eventActions';

export default class SearchFilter extends Component {

  constructor(props) {
    super(props);
  }

  handleSearchChange(event) {
    this.props.dispatch(
      setSearchTerm(
        event.target.value
      )
    );
  }
  render() {
    return (
      <div className="form-group form-icon">
        <input className="form-control" onChange={this.handleSearchChange.bind(this)}
               placeholder={this.props.label}/>
        <button type="button" className="btn btn-white btn-block" onClick={this.props.handlePage.bind(this, 1)}><i
          className="fa fa-search"></i></button>
      </div>
    );
  }
}

/*eslint-enable */
