import { useState } from 'react';
import { textToJSON } from '../utils';

const App = () => {
    const [model, setModel] = useState({
        payload: ''
    })

    function sendRequest(e) {
        e.preventDefault();
        const input = document.getElementById('input').value;

        let json = textToJSON(input)
        console.log(json)
    }

    return (
        <div className="ajax-inspector">
            <div className="left-right-content header">
                <h1>Ajax Inspector</h1>
            </div>

            <div className='body-wrapper'>
                <div className='input-wrapper'>

                    <div className="ajax-actions">
                        <div>
                            <button className="button button-default">Save</button>
                        </div>
                        <button className="button button-primary" id="ajax" onClick={sendRequest}>Send</button>
                    </div>

                    <div>
                        <textarea id="input" rows="4"></textarea>
                    </div>

                    <div>

                        <div className="wp-filter">
                            <ul className="filter-links">
                                <li className="plugin-install-featured">
                                    <a href="#" className="current" aria-current="page">Saved Request ()</a>
                                </li>
                            </ul>

                            <form id='search' className="search-form search-list" method="get">
                                <input type="search" ng-model="s" name="s" id="search-list" className="wp-filter-search" placeholder="Search list..." autocomplete="off" />
                                <input type="submit" id="search-submit" className="button hide-if-js" value="Search list" />
                            </form>
                        </div>

                        <table className="wp-list-table widefat fixed table-view-list">
                            <tr ng-className="row-selected">
                                <td ng-click="toggleSelect(row)" className="title-column">Title</td>
                                <td className="ajax-list-action">
                                    <a href="#" ng-click="remove(row)">Remove</a>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div className='response-wrapper'>
                    <p><strong>Response</strong> <strong id="status-code"></strong></p>
                    <pre id="output"></pre>
                </div>
            </div>
        </div>
    );
}

export default App; 