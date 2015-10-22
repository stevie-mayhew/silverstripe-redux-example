/*eslint-disable */

import React, {Component, PropTypes} from 'react';
import classNames from 'classnames';

export default class EventsApp extends Component {

  static propTypes = {
    count: PropTypes.number,
    page: PropTypes.number,
    handlePage: PropTypes.func
  };

  static defaultProps = {
    handlePage: () => {},
    count: 0,
    page: 1
  };

  render() {
    const {count, page, maxLinks} = this.props;

    if (count == 0) {
      return (<div></div>);
    }

    let start = page - Math.floor(maxLinks / 2);
    let end = start + maxLinks;

    if (start < 1) {
      start = 1;
      end = maxLinks + start;
    }

    if (end > count) {
      end = count;
      start = Math.max(1, end - maxLinks);
    }

    const notFirstPage = page !== 1;
    const notLastPage = count !== page;
    const pages = Array.from({length: end - start}).map((v, i) => start + i);

    return (
      <div className="pagination">
        {notFirstPage ?
        <div onClick={this.props.handlePage.bind(null, 1)}
          className="pagination-block">
          <i className="fa fa-angle-double-left"></i>
        </div> : null}
        {notFirstPage ?
        <div onClick={this.props.handlePage.bind(null, page - 1)}
          className="pagination-block prev">
          Prev
        </div> : null}
        {pages.map(v => {
          return (
            <div className={classNames('pagination-block', {active: v === page})}
              onClick={this.props.handlePage.bind(null, v)}
              key={v}
            >{v}
            </div>
          )
        })}
        {notLastPage ?
        <div onClick={this.props.handlePage.bind(null, page + 1)}
          className="pagination-block next">
          Next
        </div> : null}
        {notLastPage ?
        <div onClick={this.props.handlePage.bind(null, count)}
          className="pagination-block">
          <i className="fa fa-angle-double-right"></i>
        </div> : null}
      </div>
    );
  }

}
/*eslint-enable */
