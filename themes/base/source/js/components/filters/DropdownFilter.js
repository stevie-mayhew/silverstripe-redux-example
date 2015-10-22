/*eslint-disable */

import React, {Component, PropTypes} from 'react';

export default class DropdownFilter extends Component {
  render() {
    return (
      <div className="form-group-select">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 8.2" width="12px" height="8px"
             className="svg-chevron-down">
          <path d="M5.9 8.2L0 2.3 2.1.1l3.8 3.8 4-3.9L12 2.1"></path>
        </svg>
        <select className="form-control" onChange={this.props.change.bind(this)} value={this.props.value}>
          <option value="0" key="0">{this.props.label}</option>
          {this.props.options.map(optionType => <option key={optionType.ID}
                                                        value={optionType.ID}>{optionType.Title}</option>)}
        </select>
      </div>
    );
  }
}

/*eslint-enable */
