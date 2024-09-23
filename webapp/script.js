const thumbs = document.querySelectorAll('.thumb li');
const infoSlider = document.querySelectorAll('.info-slider');
const items = document.querySelectorAll('.item');

let index = 0;

thumbs.forEach((thumb, ind) => {
    thumb.addEventListener('click', (event) => {
        // Remove selected class from the previously selected thumbnail
        document.querySelector('.thumb .selected')?.classList.remove('selected');
        thumb.classList.add('selected');

        // Disable all thumbnails during transition
        thumbs.forEach(thum => thum.classList.add('disabled'));

        // Handle previously active item
        if (index !== ind) {
            items[index].classList.add('previously-active');
            setTimeout(() => {
                items[index].classList.remove('previously-active');
            }, 100);
        }

        // Update index and active class
        index = ind;
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
            item.classList.toggle('back-active', i === index - 1);
        });

        // Update info slider transform
        infoSlider.forEach(slide => {
            slide.style.transform = `translateY(${index * -100}%)`;
        });

        // Re-enable thumbnails after transition
        setTimeout(() => {
            thumbs.forEach(thum => thum.classList.remove('disabled'));
        }, 100);
    });
});
