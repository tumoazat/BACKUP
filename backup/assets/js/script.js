/**
 * File JavaScript chính - Hệ thống Khóa học Online
 * Tác giả: Nhóm 4 - Tú
 */

// ========== TỰ ĐỘNG ẨN THÔNG BÁO ==========
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn thông báo sau 5 giây
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Khởi tạo các chức năng
    initTooltips();
    initDragAndDrop('avatar-upload-area', 'avatar-input');
    initDragAndDrop('material-upload-area', 'material-input');
    uploadFileWithProgress('avatar-upload-form', 'avatar-progress');
    uploadFileWithProgress('material-upload-form', 'material-progress');
});

// ========== XÁC NHẬN XÓA ==========
function xácNhậnXóa(msg) {
    return confirm(msg || 'Bạn có chắc chắn muốn xóa?');
}

// ========== VALIDATE FORM ==========
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    form.querySelectorAll('[required]').forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    return isValid;
}

function validateUploadForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && !fileInput.files.length) {
        alert('Vui lòng chọn file để upload!');
        return false;
    }
    return validateForm(formId);
}

// ========== TÌM KIẾM & LỌC ==========
function tìmKiếmKhóaHọc() {
    const keyword = document.getElementById('search-keyword');
    if (keyword && keyword.value.trim()) {
        window.location.href = 'index.php?controller=course&action=search&keyword=' + encodeURIComponent(keyword.value.trim());
    }
}

function lọcTheoDanhMục(categoryId) {
    window.location.href = 'index.php?controller=course&action=index&category_id=' + categoryId;
}

// ========== PREVIEW FILE ==========
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    if (!file.type.match('image.*')) {
        alert('Vui lòng chọn file ảnh!');
        input.value = '';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        alert('Ảnh không được vượt quá 2MB!');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatar-preview');
        if (preview) preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar">';
    };
    reader.readAsDataURL(file);
}

function previewMaterial(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ];
    
    if (!allowedTypes.includes(file.type)) {
        alert('Chỉ chấp nhận file PDF, DOC, DOCX, PPT, PPTX!');
        input.value = '';
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        alert('File không được vượt quá 10MB!');
        input.value = '';
        return;
    }
    
    const fileInfo = document.getElementById('file-info');
    if (fileInfo) {
        fileInfo.innerHTML = `<strong>File:</strong> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        fileInfo.style.display = 'block';
    }
}

// ========== DRAG & DROP ==========
function initDragAndDrop(areaId, inputId) {
    const area = document.getElementById(areaId);
    const input = document.getElementById(inputId);
    if (!area || !input) return;
    
    area.addEventListener('click', () => input.click());
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
        area.addEventListener(e, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(e => {
        area.addEventListener(e, () => area.classList.add('dragover'), false);
    });
    
    ['dragleave', 'drop'].forEach(e => {
        area.addEventListener(e, () => area.classList.remove('dragover'), false);
    });
    
    area.addEventListener('drop', e => {
        input.files = e.dataTransfer.files;
        if (inputId.includes('avatar')) previewAvatar(input);
        else if (inputId.includes('material')) previewMaterial(input);
    }, false);
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// ========== UPLOAD VỚI PROGRESS ==========
function uploadFileWithProgress(formId, progressId) {
    const form = document.getElementById(formId);
    const progressBar = document.getElementById(progressId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        
        if (progressBar) {
            progressBar.style.display = 'block';
            const fill = progressBar.querySelector('.progress-fill');
            
            xhr.upload.addEventListener('progress', e => {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    fill.style.width = percent + '%';
                    fill.textContent = Math.round(percent) + '%';
                }
            });
        }
        
        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    alert(res.success ? 'Upload thành công!' : 'Lỗi: ' + res.message);
                } catch (e) {}
                window.location.reload();
            } else {
                alert('Lỗi server!');
            }
            if (progressBar) progressBar.style.display = 'none';
        });
        
        xhr.addEventListener('error', () => {
            alert('Lỗi kết nối!');
            if (progressBar) progressBar.style.display = 'none';
        });
        
        xhr.open('POST', form.action);
        xhr.send(formData);
    });
}

// ========== MODAL ==========
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

document.addEventListener('click', e => {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});

// ========== TOOLTIP ==========
function initTooltips() {
    document.querySelectorAll('.tooltip').forEach(el => {
        const text = el.getAttribute('data-tooltip');
        if (text) {
            const span = document.createElement('span');
            span.className = 'tooltip-text';
            span.textContent = text;
            el.appendChild(span);
        }
    });
}

// ========== TIỆN ÍCH ==========
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': '📄', 'doc': '📝', 'docx': '📝',
        'ppt': '📊', 'pptx': '📊', 'xls': '📊', 'xlsx': '📊',
        'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️', 'gif': '🖼️',
        'zip': '📦', 'rar': '📦'
    };
    return icons[ext] || '📎';
}
