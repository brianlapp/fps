import _ from 'lodash';

export default class BaseClass extends Object {
    constructor(data) {
        super();
        setTimeout(() => {
            if (data) {
                _(this).keys().each(key => {
                    if (data.hasOwnProperty(key)) {
                        this[key] = data[key];
                    }
                })
            }
        }, 1);
    }
}

