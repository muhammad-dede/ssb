/*
 Navicat Premium Data Transfer

 Source Server         : LOCAL
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : ssb

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 17/08/2025 22:59:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for assessment
-- ----------------------------
DROP TABLE IF EXISTS `assessment`;
CREATE TABLE `assessment`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `percentage` double NULL DEFAULT 0,
  `order` int UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assessment_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of assessment
-- ----------------------------
INSERT INTO `assessment` VALUES (1, 'AS1', 'TEKNIK DASAR', 'Latihan kontrol bola, dribbling, passing, shooting.', 40, 1, 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `assessment` VALUES (2, 'AS2', 'TAKTIK TIM', 'Pemahaman formasi, strategi bertahan dan menyerang.', 30, 2, 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `assessment` VALUES (3, 'AS3', 'FISIK & MENTAL', 'Latihan fisik, kecepatan, dan membangun karakter atlet.', 30, 3, 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');

-- ----------------------------
-- Table structure for bank
-- ----------------------------
DROP TABLE IF EXISTS `bank`;
CREATE TABLE `bank`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bank_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bank
-- ----------------------------
INSERT INTO `bank` VALUES (1, '002', 'Bank Rakyat Indonesia (BRI)', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (2, '008', 'Bank Mandiri', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (3, '009', 'Bank Negara Indonesia (BNI)', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (4, '014', 'Bank Central Asia (BCA)', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (5, '011', 'Bank Danamon', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (6, '013', 'Permata Bank', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (7, '016', 'Bank Maybank Indonesia', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (8, '022', 'CIMB Niaga', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (9, '028', 'Citibank', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (10, '031', 'Bank HSBC Indonesia', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (11, '036', 'Bank BTPN', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (12, '037', 'Bank Artha Graha Internasional', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (13, '042', 'Bank Muamalat Indonesia', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (14, '046', 'Bank DBS Indonesia', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (15, '050', 'Standard Chartered Bank', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (16, '052', 'Bank Panin', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (17, '053', 'Bank Woori Saudara Indonesia 1906', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (18, '054', 'Bank Bukopin', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (19, '057', 'Bank Bumi Arta', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (20, '059', 'Bank Mayapada Internasional', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (21, '110', 'Bank Jabar Banten (BJB)', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (22, '111', 'Bank DKI', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (23, '112', 'Bank DIY', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (24, '113', 'Bank Jateng', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (25, '114', 'Bank Jatim', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (26, '115', 'Bank Jambi', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (27, '116', 'Bank Aceh', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (28, '117', 'Bank Sumut', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (29, '118', 'Bank Nagari', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (30, '119', 'Bank Riau Kepri', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (31, '120', 'Bank Sumsel Babel', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (32, '121', 'Bank Lampung', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (33, '122', 'Bank Kalsel', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (34, '123', 'Bank Kalbar', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (35, '124', 'Bank Kaltimtara', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (36, '125', 'Bank Kalteng', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (37, '126', 'Bank Sulselbar', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (38, '127', 'Bank SulutGo', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (39, '128', 'Bank NTB Syariah', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (40, '129', 'Bank NTT', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (41, '130', 'Bank Maluku Malut', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (42, '131', 'Bank Papua', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (43, '132', 'Bank Bengkulu', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (44, '133', 'Bank Sulteng', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (45, '134', 'Bank Sultra', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank` VALUES (46, '135', 'Bank Banten', '2025-08-17 15:57:37', '2025-08-17 15:57:37');

-- ----------------------------
-- Table structure for bank_account
-- ----------------------------
DROP TABLE IF EXISTS `bank_account`;
CREATE TABLE `bank_account`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `account_holder_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bank_account_bank_code_index`(`bank_code` ASC) USING BTREE,
  CONSTRAINT `bank_account_bank_code_foreign` FOREIGN KEY (`bank_code`) REFERENCES `bank` (`code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bank_account
-- ----------------------------
INSERT INTO `bank_account` VALUES (1, '014', '2452854601', 'FATHULLOH AL HASAN', 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `bank_account` VALUES (2, '008', '1630011812073', 'FATHULLOH AL HASAN', 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');

-- ----------------------------
-- Table structure for billing
-- ----------------------------
DROP TABLE IF EXISTS `billing`;
CREATE TABLE `billing`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `billable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `billable_id` bigint UNSIGNED NOT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `due_date` date NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'UNPAID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `billing_invoice_unique`(`invoice` ASC) USING BTREE,
  INDEX `billing_billable_type_billable_id_index`(`billable_type` ASC, `billable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of billing
-- ----------------------------

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for coach
-- ----------------------------
DROP TABLE IF EXISTS `coach`;
CREATE TABLE `coach`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_of_birth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_of_birth` date NULL DEFAULT NULL,
  `gender` enum('MALE','FEMALE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `national_id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `coaching_license` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `license_issued_at` date NULL DEFAULT NULL,
  `license_expired_at` date NULL DEFAULT NULL,
  `license_issuer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'INACTIVE',
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `coach_user_id_index`(`user_id` ASC) USING BTREE,
  CONSTRAINT `coach_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of coach
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for match_event
-- ----------------------------
DROP TABLE IF EXISTS `match_event`;
CREATE TABLE `match_event`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `program_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `coach_id` bigint UNSIGNED NULL DEFAULT NULL,
  `match_date` date NULL DEFAULT NULL,
  `start_time` time NULL DEFAULT NULL,
  `end_time` time NULL DEFAULT NULL,
  `opponent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `our_score` int NULL DEFAULT NULL,
  `opponent_score` int NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `match_event_period_id_index`(`period_id` ASC) USING BTREE,
  INDEX `match_event_program_code_index`(`program_code` ASC) USING BTREE,
  INDEX `match_event_coach_id_index`(`coach_id` ASC) USING BTREE,
  CONSTRAINT `match_event_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `coach` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `match_event_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `match_event_program_code_foreign` FOREIGN KEY (`program_code`) REFERENCES `program` (`code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of match_event
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_user_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2025_05_16_042253_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (5, '2025_06_06_030935_create_bank_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_06_06_030936_create_bank_account_table', 1);
INSERT INTO `migrations` VALUES (7, '2025_06_06_030937_create_assessment_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_06_06_031552_create_program_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_06_06_031553_create_period_table', 1);
INSERT INTO `migrations` VALUES (10, '2025_06_06_031554_create_coach_table', 1);
INSERT INTO `migrations` VALUES (11, '2025_06_06_031555_create_training_table', 1);
INSERT INTO `migrations` VALUES (12, '2025_06_06_031556_create_match_event_table', 1);
INSERT INTO `migrations` VALUES (13, '2025_06_06_032151_create_student_table', 1);
INSERT INTO `migrations` VALUES (14, '2025_06_06_032339_create_student_program_table', 1);
INSERT INTO `migrations` VALUES (15, '2025_06_06_043457_create_student_training_table', 1);
INSERT INTO `migrations` VALUES (16, '2025_06_06_043651_create_student_training_assessment_table', 1);
INSERT INTO `migrations` VALUES (17, '2025_06_06_044010_create_student_match_event_table', 1);
INSERT INTO `migrations` VALUES (18, '2025_06_06_044025_create_student_match_event_assessment_table', 1);
INSERT INTO `migrations` VALUES (19, '2025_06_07_033557_create_billing_table', 1);
INSERT INTO `migrations` VALUES (20, '2025_06_07_033605_create_payment_table', 1);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (5, 'App\\Models\\User', 3);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for payment
-- ----------------------------
DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `billing_id` bigint UNSIGNED NULL DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `payment_date` date NULL DEFAULT NULL,
  `method` enum('TRANSFER','CASH') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_bank_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `receiver_account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `receiver_account_holder_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sender_bank_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sender_account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sender_account_holder_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `proof_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `payment_billing_id_index`(`billing_id` ASC) USING BTREE,
  INDEX `payment_receiver_bank_code_index`(`receiver_bank_code` ASC) USING BTREE,
  INDEX `payment_sender_bank_code_index`(`sender_bank_code` ASC) USING BTREE,
  CONSTRAINT `payment_billing_id_foreign` FOREIGN KEY (`billing_id`) REFERENCES `billing` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payment_receiver_bank_code_foreign` FOREIGN KEY (`receiver_bank_code`) REFERENCES `bank` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_sender_bank_code_foreign` FOREIGN KEY (`sender_bank_code`) REFERENCES `bank` (`code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment
-- ----------------------------

-- ----------------------------
-- Table structure for period
-- ----------------------------
DROP TABLE IF EXISTS `period`;
CREATE TABLE `period`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of period
-- ----------------------------
INSERT INTO `period` VALUES (1, 'JANUARI-JUNI 2025', '2025-01-01', '2025-06-30', 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `period` VALUES (2, 'JULI-DESEMBER 2025', '2025-07-01', '2025-12-31', 'INACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'dashboard', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (2, 'admin.role.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (3, 'admin.role.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (4, 'admin.role.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (5, 'admin.role.delete', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (6, 'admin.user.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (7, 'admin.user.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (8, 'admin.user.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (9, 'admin.user.delete', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (10, 'admin.bank-account.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (11, 'admin.bank-account.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (12, 'admin.bank-account.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (13, 'admin.bank-account.delete', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (14, 'admin.program.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (15, 'admin.program.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (16, 'admin.program.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (17, 'admin.program.delete', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (18, 'admin.period.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (19, 'admin.period.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (20, 'admin.period.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (21, 'admin.period.delete', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (22, 'admin.coach.index', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (23, 'admin.coach.create', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (24, 'admin.coach.edit', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (25, 'admin.coach.show', 'web', '2025-08-17 15:57:34', '2025-08-17 15:57:34');
INSERT INTO `permissions` VALUES (26, 'admin.coach.delete', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (27, 'admin.student.index', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (28, 'admin.student.create', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (29, 'admin.student.edit', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (30, 'admin.student.show', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (31, 'admin.student.delete', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (32, 'admin.student-program.index', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (33, 'admin.student-program.create', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (34, 'admin.student-program.edit', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (35, 'admin.student-program.show', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (36, 'admin.student-program.delete', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (37, 'admin.student-program.payment', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (38, 'admin.training.index', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (39, 'admin.training.create', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (40, 'admin.training.edit', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (41, 'admin.training.show', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (42, 'admin.training.delete', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (43, 'admin.training.generate', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (44, 'admin.training.attendance', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (45, 'admin.training.assessment', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (46, 'admin.match-event.index', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (47, 'admin.match-event.create', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (48, 'admin.match-event.edit', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (49, 'admin.match-event.show', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (50, 'admin.match-event.delete', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (51, 'admin.match-event.generate', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (52, 'admin.match-event.attendance', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (53, 'admin.match-event.assessment', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (54, 'admin.report-student.index', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (55, 'admin.report-student.show', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (56, 'admin.report-student.pdf', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (57, 'student-menu', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `permissions` VALUES (58, 'coach-menu', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');

-- ----------------------------
-- Table structure for program
-- ----------------------------
DROP TABLE IF EXISTS `program`;
CREATE TABLE `program`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_min` int NULL DEFAULT NULL,
  `age_max` int NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `registration_fee` double NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `program_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of program
-- ----------------------------
INSERT INTO `program` VALUES (1, 'U-12', 'UNDER 12', 8, 12, 'Tim untuk pemain usia di bawah 12 tahun, sebagai bagian awal akademi sepak bola.', 1000000, 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `program` VALUES (2, 'U-18', 'UNDER 18', 13, 18, 'Tim untuk pemain usia di bawah 18 tahun.', 1500000, 'ACTIVE', '2025-08-17 15:57:37', '2025-08-17 15:57:37');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (2, 1);
INSERT INTO `role_has_permissions` VALUES (3, 1);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (7, 1);
INSERT INTO `role_has_permissions` VALUES (8, 1);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (26, 1);
INSERT INTO `role_has_permissions` VALUES (27, 1);
INSERT INTO `role_has_permissions` VALUES (28, 1);
INSERT INTO `role_has_permissions` VALUES (29, 1);
INSERT INTO `role_has_permissions` VALUES (30, 1);
INSERT INTO `role_has_permissions` VALUES (31, 1);
INSERT INTO `role_has_permissions` VALUES (32, 1);
INSERT INTO `role_has_permissions` VALUES (33, 1);
INSERT INTO `role_has_permissions` VALUES (34, 1);
INSERT INTO `role_has_permissions` VALUES (35, 1);
INSERT INTO `role_has_permissions` VALUES (36, 1);
INSERT INTO `role_has_permissions` VALUES (37, 1);
INSERT INTO `role_has_permissions` VALUES (38, 1);
INSERT INTO `role_has_permissions` VALUES (39, 1);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (43, 1);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (54, 1);
INSERT INTO `role_has_permissions` VALUES (55, 1);
INSERT INTO `role_has_permissions` VALUES (56, 1);
INSERT INTO `role_has_permissions` VALUES (1, 2);
INSERT INTO `role_has_permissions` VALUES (22, 2);
INSERT INTO `role_has_permissions` VALUES (23, 2);
INSERT INTO `role_has_permissions` VALUES (24, 2);
INSERT INTO `role_has_permissions` VALUES (25, 2);
INSERT INTO `role_has_permissions` VALUES (26, 2);
INSERT INTO `role_has_permissions` VALUES (27, 2);
INSERT INTO `role_has_permissions` VALUES (28, 2);
INSERT INTO `role_has_permissions` VALUES (29, 2);
INSERT INTO `role_has_permissions` VALUES (30, 2);
INSERT INTO `role_has_permissions` VALUES (31, 2);
INSERT INTO `role_has_permissions` VALUES (32, 2);
INSERT INTO `role_has_permissions` VALUES (33, 2);
INSERT INTO `role_has_permissions` VALUES (34, 2);
INSERT INTO `role_has_permissions` VALUES (35, 2);
INSERT INTO `role_has_permissions` VALUES (36, 2);
INSERT INTO `role_has_permissions` VALUES (37, 2);
INSERT INTO `role_has_permissions` VALUES (38, 2);
INSERT INTO `role_has_permissions` VALUES (39, 2);
INSERT INTO `role_has_permissions` VALUES (40, 2);
INSERT INTO `role_has_permissions` VALUES (41, 2);
INSERT INTO `role_has_permissions` VALUES (42, 2);
INSERT INTO `role_has_permissions` VALUES (43, 2);
INSERT INTO `role_has_permissions` VALUES (44, 2);
INSERT INTO `role_has_permissions` VALUES (45, 2);
INSERT INTO `role_has_permissions` VALUES (46, 2);
INSERT INTO `role_has_permissions` VALUES (47, 2);
INSERT INTO `role_has_permissions` VALUES (48, 2);
INSERT INTO `role_has_permissions` VALUES (49, 2);
INSERT INTO `role_has_permissions` VALUES (50, 2);
INSERT INTO `role_has_permissions` VALUES (51, 2);
INSERT INTO `role_has_permissions` VALUES (52, 2);
INSERT INTO `role_has_permissions` VALUES (53, 2);
INSERT INTO `role_has_permissions` VALUES (54, 2);
INSERT INTO `role_has_permissions` VALUES (55, 2);
INSERT INTO `role_has_permissions` VALUES (56, 2);
INSERT INTO `role_has_permissions` VALUES (1, 3);
INSERT INTO `role_has_permissions` VALUES (58, 3);
INSERT INTO `role_has_permissions` VALUES (1, 4);
INSERT INTO `role_has_permissions` VALUES (57, 4);
INSERT INTO `role_has_permissions` VALUES (1, 5);
INSERT INTO `role_has_permissions` VALUES (22, 5);
INSERT INTO `role_has_permissions` VALUES (25, 5);
INSERT INTO `role_has_permissions` VALUES (27, 5);
INSERT INTO `role_has_permissions` VALUES (30, 5);
INSERT INTO `role_has_permissions` VALUES (32, 5);
INSERT INTO `role_has_permissions` VALUES (35, 5);
INSERT INTO `role_has_permissions` VALUES (38, 5);
INSERT INTO `role_has_permissions` VALUES (41, 5);
INSERT INTO `role_has_permissions` VALUES (46, 5);
INSERT INTO `role_has_permissions` VALUES (49, 5);
INSERT INTO `role_has_permissions` VALUES (54, 5);
INSERT INTO `role_has_permissions` VALUES (55, 5);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Super Admin', 'web', '2025-08-17 15:57:35', '2025-08-17 15:57:35');
INSERT INTO `roles` VALUES (2, 'Admin', 'web', '2025-08-17 15:57:36', '2025-08-17 15:57:36');
INSERT INTO `roles` VALUES (3, 'Coach', 'web', '2025-08-17 15:57:36', '2025-08-17 15:57:36');
INSERT INTO `roles` VALUES (4, 'Student', 'web', '2025-08-17 15:57:36', '2025-08-17 15:57:36');
INSERT INTO `roles` VALUES (5, 'Leader', 'web', '2025-08-17 15:57:36', '2025-08-17 15:57:36');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_of_birth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_of_birth` date NULL DEFAULT NULL,
  `gender` enum('MALE','FEMALE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `national_id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dominant_foot` enum('RIGHT','LEFT','BOTH') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `height_cm` double NULL DEFAULT NULL,
  `weight_kg` double NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_user_id_index`(`user_id` ASC) USING BTREE,
  CONSTRAINT `student_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student
-- ----------------------------

-- ----------------------------
-- Table structure for student_match_event
-- ----------------------------
DROP TABLE IF EXISTS `student_match_event`;
CREATE TABLE `student_match_event`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `match_event_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attendance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_match_event_student_id_index`(`student_id` ASC) USING BTREE,
  INDEX `student_match_event_match_event_id_index`(`match_event_id` ASC) USING BTREE,
  CONSTRAINT `student_match_event_match_event_id_foreign` FOREIGN KEY (`match_event_id`) REFERENCES `match_event` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_match_event_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_match_event
-- ----------------------------

-- ----------------------------
-- Table structure for student_match_event_assessment
-- ----------------------------
DROP TABLE IF EXISTS `student_match_event_assessment`;
CREATE TABLE `student_match_event_assessment`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_match_event_id` bigint UNSIGNED NULL DEFAULT NULL,
  `assessment_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `value` double NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_match_event_assessment_student_match_event_id_index`(`student_match_event_id` ASC) USING BTREE,
  INDEX `student_match_event_assessment_assessment_code_index`(`assessment_code` ASC) USING BTREE,
  CONSTRAINT `student_match_event_assessment_assessment_code_foreign` FOREIGN KEY (`assessment_code`) REFERENCES `assessment` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_match_event_assessment_student_match_event_id_foreign` FOREIGN KEY (`student_match_event_id`) REFERENCES `student_match_event` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_match_event_assessment
-- ----------------------------

-- ----------------------------
-- Table structure for student_program
-- ----------------------------
DROP TABLE IF EXISTS `student_program`;
CREATE TABLE `student_program`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `program_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'UNREGISTERED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_program_student_id_index`(`student_id` ASC) USING BTREE,
  INDEX `student_program_program_code_index`(`program_code` ASC) USING BTREE,
  INDEX `student_program_period_id_index`(`period_id` ASC) USING BTREE,
  CONSTRAINT `student_program_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_program_program_code_foreign` FOREIGN KEY (`program_code`) REFERENCES `program` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_program_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_program
-- ----------------------------

-- ----------------------------
-- Table structure for student_training
-- ----------------------------
DROP TABLE IF EXISTS `student_training`;
CREATE TABLE `student_training`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `training_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attendance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_training_student_id_index`(`student_id` ASC) USING BTREE,
  INDEX `student_training_training_id_index`(`training_id` ASC) USING BTREE,
  CONSTRAINT `student_training_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_training_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_training
-- ----------------------------

-- ----------------------------
-- Table structure for student_training_assessment
-- ----------------------------
DROP TABLE IF EXISTS `student_training_assessment`;
CREATE TABLE `student_training_assessment`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_training_id` bigint UNSIGNED NULL DEFAULT NULL,
  `assessment_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `value` double NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_training_assessment_student_training_id_index`(`student_training_id` ASC) USING BTREE,
  INDEX `student_training_assessment_assessment_code_index`(`assessment_code` ASC) USING BTREE,
  CONSTRAINT `student_training_assessment_assessment_code_foreign` FOREIGN KEY (`assessment_code`) REFERENCES `assessment` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_training_assessment_student_training_id_foreign` FOREIGN KEY (`student_training_id`) REFERENCES `student_training` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_training_assessment
-- ----------------------------

-- ----------------------------
-- Table structure for training
-- ----------------------------
DROP TABLE IF EXISTS `training`;
CREATE TABLE `training`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `program_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `coach_id` bigint UNSIGNED NULL DEFAULT NULL,
  `training_date` date NULL DEFAULT NULL,
  `start_time` time NULL DEFAULT NULL,
  `end_time` time NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `training_period_id_index`(`period_id` ASC) USING BTREE,
  INDEX `training_program_code_index`(`program_code` ASC) USING BTREE,
  INDEX `training_coach_id_index`(`coach_id` ASC) USING BTREE,
  CONSTRAINT `training_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `coach` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `training_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `training_program_code_foreign` FOREIGN KEY (`program_code`) REFERENCES `program` (`code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of training
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'Super Admin', 'super.admin@ssb.com', '2025-08-17 15:57:36', '$2y$12$zSB9Mw/dtJ.Q4XFP.bhA0.bou2EMqV.4x/qKSoRGqvA/yhi8OnIG.', 'ACTIVE', NULL, '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `user` VALUES (2, 'Admin SSB', 'admin@ssb.com', '2025-08-17 15:57:37', '$2y$12$PjuM.PlaafBMiHuE2VW/keOQXCN3GsbMMG2ouhqqQzD/tDQB.bHs2', 'ACTIVE', NULL, '2025-08-17 15:57:37', '2025-08-17 15:57:37');
INSERT INTO `user` VALUES (3, 'Pimpinan SSB', 'leader@ssb.com', '2025-08-17 15:57:37', '$2y$12$q3HjP678FRF3K00cDC743e75l1uG4S9hnueoVVt6Gjsug.YvFgL/y', 'ACTIVE', NULL, '2025-08-17 15:57:37', '2025-08-17 15:57:37');

SET FOREIGN_KEY_CHECKS = 1;
