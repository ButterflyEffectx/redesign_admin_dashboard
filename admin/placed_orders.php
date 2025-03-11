<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];

   // ตรวจสอบให้แน่ใจว่ามีการส่งสถานะการชำระเงิน
   if ($payment_status != '') {
      $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE order_id = ?");
      $update_status->execute([$payment_status, $order_id]);

      $message[] = 'อัปเดตสถานะการชำระเงินเรียบร้อย!';
   } else {
      $message[] = 'กรุณาเลือกสถานะการชำระเงิน';
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE order_id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>คำสั่งซื้อที่ได้รับ</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

   <!-- Bootstrap Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css">
</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <section class="">
      <h1 class="text-center mb-4">คำสั่งซื้อที่ได้รับ</h1>

      <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();

      // ฟังก์ชันสำหรับแปลงสถานะเป็นแถบสี
      function getStatusBadge($status)
      {
         if ($status == 'จ่ายตังแล้ว') {
            return '<span class="badge bg-success rounded-pill px-3 py-2">
              <i class="fas fa-check-circle me-1"></i> จ่ายตังแล้ว
            </span>';
         } else {
            return '<span class="badge bg-warning rounded-pill px-3 py-2">
              <i class="fas fa-clock me-1"></i> ยังไม่จ่ายตัง
            </span>';
         }
      }

      // ฟังก์ชันสำหรับแปลงวิธีการชำระเงินเป็นไอคอน
      function getPaymentMethodIcon($method)
      {
         switch (strtolower($method)) {
            case 'paypal':
               return '<i class="fab fa-paypal text-primary me-1"></i> ' . $method;
            case 'credit card':
            case 'บัตรเครดิต':
               return '<i class="far fa-credit-card text-info me-1"></i> ' . $method;
            case 'cash':
            case 'เงินสด':
               return '<i class="fas fa-money-bill-wave text-success me-1"></i> ' . $method;
            case 'bank transfer':
            case 'โอนเงิน':
               return '<i class="fas fa-university text-primary me-1"></i> ' . $method;
            default:
               return '<i class="fas fa-money-check text-secondary me-1"></i> ' . $method;
         }
      }
      ?>

      <div class="mx-4">
         <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-gradient-primary ">
               <div class="d-flex justify-content-between align-items-center">
                  <h5 class="mb-0"><i class="fas fa-shopping-cart me-2 "></i>จัดการคำสั่งซื้อ</h5>
                  <div>
                     <button class="btn btn-sm btn-light" id="refresh-btn">
                        <i class="fas fa-sync-alt"></i> รีเฟรช
                     </button>
                     <a href="#" class="btn btn-sm btn-light ms-2" id="export-btn">
                        <i class="fas fa-file-export"></i> ส่งออกข้อมูล
                     </a>
                  </div>
               </div>
            </div>

            <div class="card-body">
               <?php if ($select_orders->rowCount() > 0): ?>
                  <div class="row mb-3">
                     <div class="col-md-3">
                        <div class="input-group">
                           <span class="input-group-text bg-light border-0">
                              <i class="fas fa-search"></i>
                           </span>
                           <input type="text" class="form-control border-0 bg-light" id="orderSearch"
                              placeholder="ค้นหาคำสั่งซื้อ...">
                        </div>
                     </div>
                     <div class="col-md-3 ms-auto text-end">
                        <select class="form-select bg-light border-0" id="statusFilter">
                           <option value="all">ทุกสถานะ</option>
                           <option value="จ่ายตังแล้ว">จ่ายตังแล้ว</option>
                           <option value="ยังไม่จ่ายตัง">ยังไม่จ่ายตัง</option>
                        </select>
                     </div>
                  </div>

                  <div class="table-responsive">
                     <table class="table table-hover align-middle border-top">
                        <thead class="table-light">
                           <tr>
                              <th><i class="fas fa-hashtag me-1"></i> รหัส</th>
                              <th><i class="fas fa-user me-1"></i> ลูกค้า</th>
                              <th><i class="fas fa-info-circle me-1"></i> รายละเอียด</th>
                              <th><i class="fas fa-money-bill me-1"></i> ยอดเงิน</th>
                              <th><i class="fas fa-calendar-alt me-1"></i> วันที่</th>
                              <th><i class="fas fa-credit-card me-1"></i> สถานะ</th>
                              <th width="200"><i class="fas fa-tools me-1"></i> จัดการ</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)): ?>
                              <tr class="order-row" data-status="<?= $fetch_orders['payment_status']; ?>">
                                 <td>
                                    <span class="fw-bold text-primary">#<?= $fetch_orders['order_id']; ?></span>
                                 </td>
                                 <td>
                                    <div class="d-flex align-items-center">
                                       <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center me-2"
                                          style="width: 40px; height: 40px; line-height: 40px;">
                                          <span
                                             class="text-primary fw-bold"><?= strtoupper(substr($fetch_orders['name'], 0, 1)); ?></span>
                                       </div>
                                       <div>
                                          <h6 class="mb-0"><?= $fetch_orders['name']; ?></h6>
                                          <small class="text-muted">
                                             <i class="fas fa-envelope me-1"></i> <?= $fetch_orders['email']; ?>
                                          </small><br>
                                          <small class="text-muted">
                                             <i class="fas fa-phone me-1"></i> <?= $fetch_orders['number']; ?>
                                          </small>
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <small class="text-muted d-block">
                                       <i class="fas fa-map-marker-alt me-1"></i> <?= $fetch_orders['address']; ?>
                                    </small>
                                    <small class="text-muted d-block">
                                       <i class="fas fa-user-tag me-1"></i> ID: <?= $fetch_orders['user_id']; ?>
                                    </small>
                                    <small class="text-muted d-block">
                                       <?= getPaymentMethodIcon($fetch_orders['method']); ?>
                                    </small>
                                 </td>
                                 <td>
                                    <h6 class="mb-0 fw-bold"><?= number_format($fetch_orders['total_price'], 2); ?> บาท</h6>
                                 </td>
                                 <td>
                                    <small class="text-muted">
                                       <i class="far fa-calendar-alt me-1"></i>
                                       <?= date('d/m/Y', strtotime($fetch_orders['placed_on'])); ?>
                                    </small><br>
                                    <small class="text-muted">
                                       <i class="far fa-clock me-1"></i>
                                       <?= date('H:i', strtotime($fetch_orders['placed_on'])); ?> น.
                                    </small>
                                 </td>
                                 <td>
                                    <?= getStatusBadge($fetch_orders['payment_status']); ?>
                                 </td>
                                 <td>
                                    <form id="form_<?= $fetch_orders['order_id']; ?>" method="POST" action=""
                                       class="d-flex gap-1 flex-wrap">
                                       <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id']; ?>">

                                       <div class="input-group input-group-sm">
                                          <select id="status_<?= $fetch_orders['order_id']; ?>" name="payment_status"
                                             class="form-select form-select-sm border-0 bg-light">
                                             <option value="" selected disabled><?= $fetch_orders['payment_status']; ?>
                                             </option>
                                             <option value="ยังไม่จ่ายตัง">ยังไม่จ่ายตัง</option>
                                             <option value="จ่ายตังแล้ว">จ่ายตังแล้ว</option>
                                          </select>

                                       </div>

                                       <div class="btn-group btn-group-sm d-flex justify-content-center mx-auto ">
                                          <button type="submit" class="btn btn-sm btn-primary" name="update_payment">
                                             <i class="fas fa-save">อัปเดต</i>
                                          </button>
                                          <a href="../1.php?order_id=<?= $fetch_orders['order_id']; ?>"
                                             class="btn btn-warning">
                                             <i class="fas fa-file-invoice">แก้ไข</i>
                                          </a>
                                          <a href="placed_orders.php?delete=<?= $fetch_orders['order_id']; ?>"
                                             class="btn btn-danger"
                                             onclick="return confirm('คุณต้องการลบคำสั่งซื้อนี้ใช่หรือไม่?');">
                                             <i class="fas fa-trash">ลบ</i>
                                          </a>
                                       </div>
                                    </form>
                                 </td>
                              </tr>
                           <?php endwhile; ?>
                        </tbody>
                     </table>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mt-3">
                     <div class="text-muted small">
                        แสดง <?= $select_orders->rowCount(); ?> รายการ
                     </div>
                     <nav>
                        <ul class="pagination pagination-sm mb-0">
                           <li class="page-item disabled"><a class="page-link" href="#">ก่อนหน้า</a></li>
                           <li class="page-item active"><a class="page-link" href="#">1</a></li>
                           <li class="page-item"><a class="page-link" href="#">2</a></li>
                           <li class="page-item"><a class="page-link" href="#">3</a></li>
                           <li class="page-item"><a class="page-link" href="#">ถัดไป</a></li>
                        </ul>
                     </nav>
                  </div>
               <?php else: ?>
                  <div class="alert alert-info d-flex align-items-center" role="alert">
                     <i class="fas fa-info-circle fs-4 me-2"></i>
                     <div>ไม่มีคำสั่งซื้อในระบบ!</div>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </div>

      <script>
         // เพิ่ม JavaScript สำหรับฟิลเตอร์และค้นหา
         document.addEventListener('DOMContentLoaded', function () {
            // ฟังก์ชันค้นหาคำสั่งซื้อ
            const searchInput = document.getElementById('orderSearch');
            if (searchInput) {
               searchInput.addEventListener('input', filterOrders);
            }

            // ฟังก์ชันกรองตามสถานะ
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
               statusFilter.addEventListener('change', filterOrders);
            }

            // ฟังก์ชันกรองรายการ
            function filterOrders() {
               const searchTerm = searchInput.value.toLowerCase();
               const statusValue = statusFilter.value;
               const rows = document.querySelectorAll('.order-row');

               rows.forEach(row => {
                  const rowText = row.textContent.toLowerCase();
                  const rowStatus = row.dataset.status;

                  const matchesSearch = searchTerm === '' || rowText.includes(searchTerm);
                  const matchesStatus = statusValue === 'all' || rowStatus === statusValue;

                  row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
               });
            }

            // รีเฟรชหน้า
            const refreshBtn = document.getElementById('refresh-btn');
            if (refreshBtn) {
               refreshBtn.addEventListener('click', function () {
                  location.reload();
               });
            }
         });
      </script>
      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>