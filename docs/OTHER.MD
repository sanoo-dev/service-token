# Improve project

- Nghiên cứu bỏ session trên SSO Server (package laravel-sso), sử dụng trực tiếp từ sessionBrokerData.
(Hiện tại session hơi dư thừa vì sessionBrokerData cơ bản cũng đã chứa các thông tin cần thiết)
- API refresh-token: Thêm refresh_token để refresh token hết hạn. (Hiện tại sử dụng token cũ để request lấy token mới)
- Caching một số data (info member, ...) để cải thiện hiệu suất.
- Refactor code.
