/*eslint-disable */

import React, {Component, PropTypes} from 'react';

export default class ResetFilters extends Component {
  render() {
    return (
      <button type="button" className="btn btn-white btn-block" onClick={this.props.clearFilters}><i
        className="fa fa-refresh"></i> Reset</button>
    );
  }
}

/*eslint-enable */

