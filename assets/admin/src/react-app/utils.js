function textToJSON(text) {
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

export {
    textToJSON
}