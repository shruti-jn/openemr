-- -----------------------------------------------------------------------
-- Example / mock lab result data for OpenEMR
-- -----------------------------------------------------------------------
-- Run this AFTER example_patient_data.sql (or ensure patient_data has the
-- same pids). Uses patient_id 1, 4, 5, 41 from example_patient_data.sql.
-- UUIDs are left NULL; OpenEMR will backfill via UuidRegistry when needed.
-- -----------------------------------------------------------------------

-- Patient 1 (Ted Shaw): CBC + BMP from 2 months ago
INSERT INTO `procedure_order` (
  `provider_id`, `patient_id`, `encounter_id`, `date_collected`, `date_ordered`,
  `order_priority`, `order_status`, `activity`, `procedure_order_type`
) VALUES (
  1, 1, 0, '2024-12-15 08:00:00', '2024-12-14 16:00:00',
  'normal', 'complete', 1, 'laboratory_test'
);

SET @po_id = LAST_INSERT_ID();

INSERT INTO `procedure_order_code` (
  `procedure_order_id`, `procedure_order_seq`, `procedure_code`, `procedure_name`, `procedure_source`, `do_not_send`
) VALUES
  (@po_id, 1, '58410-2', 'CBC with differential', '1', 0),
  (@po_id, 2, '24320-4', 'Comprehensive metabolic panel', '1', 0);

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 1, '2024-12-15 08:00:00', '2024-12-15 10:30:00', 1, 'SP-001', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '789-8', 'RBC', '10*6/uL', '4.82', '4.20-5.40', 'no', 'final'),
  (@pr_id, 'N', '718-7', 'Hemoglobin', 'g/dL', '14.2', '13.0-17.0', 'no', 'final'),
  (@pr_id, 'N', '4544-3', 'Hematocrit', '%', '42.1', '39.0-50.0', 'no', 'final'),
  (@pr_id, 'N', '787-2', 'MCV', 'fL', '88', '80-100', 'no', 'final'),
  (@pr_id, 'N', '785-6', 'WBC', '10*3/uL', '7.1', '4.5-11.0', 'no', 'final'),
  (@pr_id, 'N', '777-3', 'Platelets', '10*3/uL', '245', '150-400', 'no', 'final');

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 2, '2024-12-15 08:00:00', '2024-12-15 11:00:00', 1, 'SP-001', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '2345-7', 'Glucose', 'mg/dL', '98', '70-100', 'no', 'final'),
  (@pr_id, 'N', '2160-0', 'Creatinine', 'mg/dL', '1.0', '0.7-1.3', 'no', 'final'),
  (@pr_id, 'N', '3094-0', 'BUN', 'mg/dL', '14', '7-20', 'no', 'final'),
  (@pr_id, 'N', '2951-2', 'Sodium', 'mmol/L', '140', '136-145', 'no', 'final'),
  (@pr_id, 'N', '2823-3', 'Potassium', 'mmol/L', '4.2', '3.5-5.0', 'no', 'final'),
  (@pr_id, 'N', '3097-6', 'Chloride', 'mmol/L', '102', '98-106', 'no', 'final'),
  (@pr_id, 'N', '17861-6', 'Calcium', 'mg/dL', '9.4', '8.6-10.2', 'no', 'final'),
  (@pr_id, 'N', '2885-2', 'Protein total', 'g/dL', '7.0', '6.0-8.3', 'no', 'final');

-- Patient 5 (Farrah Rolle): Urinalysis + HbA1c from 1 month ago
INSERT INTO `procedure_order` (
  `provider_id`, `patient_id`, `encounter_id`, `date_collected`, `date_ordered`,
  `order_priority`, `order_status`, `activity`, `procedure_order_type`
) VALUES (
  1, 5, 0, '2025-01-20 07:30:00', '2025-01-19 14:00:00',
  'normal', 'complete', 1, 'laboratory_test'
);

SET @po_id = LAST_INSERT_ID();

INSERT INTO `procedure_order_code` (
  `procedure_order_id`, `procedure_order_seq`, `procedure_code`, `procedure_name`, `procedure_source`, `do_not_send`
) VALUES
  (@po_id, 1, '24357-6', 'Urinalysis macro (dipstick) panel', '1', 0),
  (@po_id, 2, '4548-4', 'Hemoglobin A1c', '1', 0);

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 1, '2025-01-20 07:30:00', '2025-01-20 09:00:00', 1, 'SP-005A', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'S', '5799-2', 'Urine appearance', '', 'Clear', '', 'no', 'final'),
  (@pr_id, 'N', '5803-2', 'Urine pH', '[pH]', '6.0', '5.0-8.0', 'no', 'final'),
  (@pr_id, 'S', '20405-4', 'Urine protein', '', 'Negative', 'Negative', 'no', 'final'),
  (@pr_id, 'S', '2514-8', 'Urine glucose', '', 'Negative', 'Negative', 'no', 'final'),
  (@pr_id, 'S', '2514-8', 'Urine ketone', '', 'Negative', 'Negative', 'no', 'final'),
  (@pr_id, 'S', '25428-4', 'Urine nitrite', '', 'Negative', 'Negative', 'no', 'final');

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 2, '2025-01-20 07:30:00', '2025-01-20 10:00:00', 1, 'SP-005B', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '4548-4', 'Hemoglobin A1c', '%', '5.6', '4.0-5.6', 'no', 'final');

-- Patient 41 (Brent Perez): Lipid panel + TSH from 3 weeks ago
INSERT INTO `procedure_order` (
  `provider_id`, `patient_id`, `encounter_id`, `date_collected`, `date_ordered`,
  `order_priority`, `order_status`, `activity`, `procedure_order_type`
) VALUES (
  1, 41, 0, '2025-02-02 08:15:00', '2025-02-01 17:00:00',
  'normal', 'complete', 1, 'laboratory_test'
);

SET @po_id = LAST_INSERT_ID();

INSERT INTO `procedure_order_code` (
  `procedure_order_id`, `procedure_order_seq`, `procedure_code`, `procedure_name`, `procedure_source`, `do_not_send`
) VALUES
  (@po_id, 1, '57698-3', 'Lipid panel', '1', 0),
  (@po_id, 2, '3016-3', 'TSH', '1', 0);

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 1, '2025-02-02 08:15:00', '2025-02-02 12:00:00', 1, 'SP-041', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '2093-3', 'Cholesterol total', 'mg/dL', '198', '<200', 'no', 'final'),
  (@pr_id, 'N', '2085-9', 'Cholesterol in LDL', 'mg/dL', '112', '<100', 'high', 'final'),
  (@pr_id, 'N', '2086-7', 'Cholesterol in HDL', 'mg/dL', '48', '>40', 'no', 'final'),
  (@pr_id, 'N', '2571-8', 'Triglycerides', 'mg/dL', '145', '<150', 'no', 'final');

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 2, '2025-02-02 08:15:00', '2025-02-02 11:30:00', 1, 'SP-041', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '3016-3', 'TSH', 'mIU/L', '2.4', '0.5-5.0', 'no', 'final');

-- Patient 4 (Eduardo Perez): CBC only, one slightly low result (mock abnormal)
INSERT INTO `procedure_order` (
  `provider_id`, `patient_id`, `encounter_id`, `date_collected`, `date_ordered`,
  `order_priority`, `order_status`, `activity`, `procedure_order_type`
) VALUES (
  1, 4, 0, '2025-01-10 09:00:00', '2025-01-09 15:00:00',
  'normal', 'complete', 1, 'laboratory_test'
);

SET @po_id = LAST_INSERT_ID();

INSERT INTO `procedure_order_code` (
  `procedure_order_id`, `procedure_order_seq`, `procedure_code`, `procedure_name`, `procedure_source`, `do_not_send`
) VALUES (@po_id, 1, '58410-2', 'CBC with differential', '1', 0);

INSERT INTO `procedure_report` (
  `procedure_order_id`, `procedure_order_seq`, `date_collected`, `date_report`, `source`, `specimen_num`, `report_status`, `review_status`
) VALUES (@po_id, 1, '2025-01-10 09:00:00', '2025-01-10 11:00:00', 1, 'SP-004', 'complete', 'reviewed');

SET @pr_id = LAST_INSERT_ID();

INSERT INTO `procedure_result` (
  `procedure_report_id`, `result_data_type`, `result_code`, `result_text`, `units`, `result`, `range`, `abnormal`, `result_status`
) VALUES
  (@pr_id, 'N', '789-8', 'RBC', '10*6/uL', '4.10', '4.20-5.40', 'low', 'final'),
  (@pr_id, 'N', '718-7', 'Hemoglobin', 'g/dL', '12.8', '13.0-17.0', 'low', 'final'),
  (@pr_id, 'N', '4544-3', 'Hematocrit', '%', '38.2', '39.0-50.0', 'low', 'final'),
  (@pr_id, 'N', '785-6', 'WBC', '10*3/uL', '6.8', '4.5-11.0', 'no', 'final'),
  (@pr_id, 'N', '777-3', 'Platelets', '10*3/uL', '198', '150-400', 'no', 'final');
