// Carousel
const gap = 16;

function setupCarousel(carouselId, contentId, nextId, prevId) {
  const carousel = document.getElementById(carouselId),
    content = document.getElementById(contentId),
    next = document.getElementById(nextId),
    prev = document.getElementById(prevId);

  let width = carousel.offsetWidth;

  next.addEventListener("click", (e) => {
    carousel.scrollBy(width + gap, 0);
    if (carousel.scrollWidth !== 0) {
      prev.style.display = "flex";
    }
    if (content.scrollWidth - width - gap <= carousel.scrollLeft + width) {
      next.style.display = "none";
    }
  });

  prev.addEventListener("click", (e) => {
    carousel.scrollBy(-(width + gap), 0);
    if (carousel.scrollLeft - width - gap <= 0) {
      prev.style.display = "none";
    }
    if (!(content.scrollWidth - width - gap <= carousel.scrollLeft + width)) {
      next.style.display = "flex";
    }
  });

  window.addEventListener("resize", (e) => (width = carousel.offsetWidth));
}

// Cài đặt carousel
setupCarousel("carousel-hot", "content-hot", "next-hot", "prev-hot");
setupCarousel("carousel-bestseller", "content-bestseller", "next-bestseller", "prev-bestseller");