//* Chuyển các sản phẩm đang giảm giá thành dạng carousel
$(document).ready(function() {
    $(".flashsale-carousel").owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
});

//* Khởi tạo đồng hồ đếm ngược cho từng mục trong carousel flashsale
document.addEventListener('DOMContentLoaded', function() {
    //* Khởi tạo biến và hàm
    var owl = $('.flashsale-carousel'); // Biến chứa carousel
    var countdownInterval; // Biến chứa khoảng tg của đồng hồ đếm ngược

    //* Hàm khởi tạo đồng hồ đếm ngược
    function initializeCountdown(endTime, countdownElement) {
        clearInterval(countdownInterval); // Xóa interval cũ
        countdownInterval = setInterval(function() { // Tạo interval mới
            var now = new Date().getTime(); // Lấy thời gian hiện tại
            var distance = new Date(endTime).getTime() - now; // Tính khoảng cách thời gian còn lại

            //* Nếu thời gian còn lại < 0 thì dừng đồng hồ và hiển thị "EXPIRED"
            if (distance < 0) {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = "EXPIRED";
                return;
            }

            //* Tính giờ, phút, giây
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            //* Hiển thị giờ, phút, giây
            countdownElement.innerHTML = hours + "h " + minutes + "m " + seconds + "s ";
        }, 1000);
    }

    //* Sự kiện khi carousel thay đổi
    owl.on('changed.owl.carousel', function(event) {
        // Lấy ra mục hiện tại
        var currentItem = $(event.target).find('.owl-item').eq(event.item.index).find('.container-fluid');
        // Lấy ra thời gian kết thúc của mục hiện tại
        var endTime = currentItem.data('endtime');
        // Lấy ra element chứa đồng hồ đếm ngược của mục hiện tại
        var countdownElement = currentItem.find('#flashsale-countdown')[0];
        // Khởi tạo đồng hồ đếm ngược cho mục hiện tại
        initializeCountdown(endTime, countdownElement);
    });

    //* Khởi tạo đồng hồ đếm ngược cho mục đầu tiên
    // Lấy ra mục đầu tiên
    var firstItem = owl.find('.owl-item').eq(0).find('.container-fluid');
    // Lấy ra thời gian kết thúc của mục đầu tiên
    var firstEndTime = firstItem.data('endtime');
    // Lấy ra element chứa đồng hồ đếm ngược của mục đầu
    var firstCountdownElement = firstItem.find('#flashsale-countdown')[0];
    // Khởi tạo đồng hồ đếm ngược cho mục đầu tiên
    initializeCountdown(firstEndTime, firstCountdownElement);
});