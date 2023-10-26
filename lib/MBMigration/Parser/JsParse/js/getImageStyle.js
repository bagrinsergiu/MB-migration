const data = '{{selector}}';

const getImagesSize = (data) => {

    const node = document.querySelector(data);
    if (!node) {
        return JSON.stringify({
            error: `Element with selector ${data} not found`
        });
    }

    const images = node.querySelectorAll("img");

    const result = [];

    images.forEach((image) => {
        const src = image.src || image.srcset;
        const width = image.width;
        const height = image.height;
        result.push({src,width, height});
    })

    return JSON.stringify(result);
}

getImagesSize(data);