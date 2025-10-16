<?php
include("config.php");
?>

<!-- Modal Structure -->
<div id="addStaffModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
    <!-- Modal Header -->
    <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2">
      <h3 class="text-xl font-bold text-gray-800">Tambah Staff Baru</h3>
      <button id="closeModal" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <!-- Modal Form -->
    <form id="addStaffForm" method="POST" action="add_staff_process.php">
      <div class="space-y-4">
        <!-- Data Login -->
        <div class="border-b pb-3">
          <h4 class="font-semibold text-gray-700 mb-2">Data Login</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
              <input type="text" name="username" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input type="password" name="password" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
            </div>
          </div>
        </div>

        <!-- Data Pribadi -->
        <div class="border-b pb-3">
          <h4 class="font-semibold text-gray-700 mb-2">Data Pribadi</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
              <input type="text" name="nama" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
              <textarea name="alamat" rows="2" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
                  <option value="">Pilih</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                <select name="agama" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
                  <option value="">Pilih</option>
                  <option value="Islam">Islam</option>
                  <option value="Kristen">Kristen</option>
                  <option value="Katolik">Katolik</option>
                  <option value="Hindu">Hindu</option>
                  <option value="Buddha">Buddha</option>
                  <option value="Konghucu">Konghucu</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" name="email" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
            </div>
          </div>
        </div>

        <!-- Data Pekerjaan -->
        <div>
          <h4 class="font-semibold text-gray-700 mb-2">Data Pekerjaan</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Departemen *</label>
              <select name="departemen" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
                <option value="">Pilih Departemen</option>
                <option value="Dapur">Dapur</option>
                <option value="Pelayanan">Pelayanan</option>
                <option value="Kasir">Kasir</option>
                <option value="Manajemen">Manajemen</option>
                <option value="Bar">Bar</option>
                <option value="Kebersihan">Kebersihan</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan *</label>
              <select name="jabatan" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
                <option value="">Pilih Jabatan</option>
                <option value="Kepala Departemen">Kepala Departemen</option>
                <option value="Staff">Staff</option>
                <option value="Waitress">Waitress</option>
                <option value="Barista">Barista</option>
                <option value="Kasir">Kasir</option>
                <option value="Chef">Chef</option>
                <option value="Sous Chef">Sous Chef</option>
                <option value="Manager">Manager</option>
                <option value="Supervisor">Supervisor</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
              <select name="status" required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6b4b3e]">
                <option value="Aktif">Aktif</option>
                <option value="Non-Aktif">Non-Aktif</option>
                <option value="Cuti">Cuti</option>
                <option value="Training">Training</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Actions -->
      <div class="flex justify-end gap-3 mt-6 sticky bottom-0 bg-white py-2">
        <button type="button" id="cancelBtn" 
          class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
          Batal
        </button>
        <button type="submit" 
          class="px-4 py-2 bg-[#6b4b3e] text-white rounded-md hover:bg-[#5a3f32]">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('addStaffModal');
  const openBtn = document.getElementById('openAddStaff');
  const closeBtn = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelBtn');
  const form = document.getElementById('addStaffForm');

  // Open modal
  if (openBtn) {
    openBtn.addEventListener('click', function() {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
  }

  // Close modal
  function closeModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

  // Close modal when clicking outside
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Form submission
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Simple validation for required fields
      const requiredFields = form.querySelectorAll('[required]');
      let valid = true;
      
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add('border-red-500');
        } else {
          field.classList.remove('border-red-500');
        }
      });

      if (valid) {
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;

        form.submit();
      } else {
        alert('Harap lengkapi semua field yang wajib diisi!');
      }
    });
  }

  // Real-time validation
  const inputs = document.querySelectorAll('input, select, textarea');
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      if (this.hasAttribute('required') && !this.value.trim()) {
        this.classList.add('border-red-500');
      } else {
        this.classList.remove('border-red-500');
      }
    });
  });
});
</script>