exports.compareVersion = function (v1, comparator, v2) {
    "use strict";
    var comparator = comparator == '=' ? '==' : comparator;
    if (['==', '===', '<', '<=', '>', '>=', '!=', '!=='].indexOf(comparator) == -1) {
        throw new Error('Invalid comparator. ' + comparator);
    }
    var v1parts = v1.split('.'), v2parts = v2.split('.');
    var maxLen = Math.max(v1parts.length, v2parts.length);
    var part1, part2;
    var cmp = 0;
    for (var i = 0; i < maxLen && !cmp; i++) {
        part1 = parseInt(v1parts[i], 10) || 0;
        part2 = parseInt(v2parts[i], 10) || 0;
        if (part1 < part2)
            cmp = 1;
        if (part1 > part2)
            cmp = -1;
    }
    return eval('0' + comparator + cmp);
}

exports.textToJSON = function (text) {
    const jsonObject = {};
    const lines = text.split('\n');

    lines.forEach(line => {
        const [key, value] = line.split(':').map(item => item.trim());
        if (key) {
            jsonObject[key] = isNaN(value) ? value : Number(value);
        }
    });

    return jsonObject;
}

exports.isValidJSON = function(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

exports.toFormData = function(obj) {
    let formData = new FormData();
    for (let [key, val] of Object.entries(obj)) {
        formData.append(key, val);
    }
    return formData;
}