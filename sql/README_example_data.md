# Example / sample data for OpenEMR

Optional SQL scripts that load mock data for development and testing.

| File | Description |
|------|-------------|
| `example_patient_data.sql` | Sample patient demographics (e.g. Farrah Rolle, Ted Shaw). |
| `example_patient_users.sql` | Users/providers referenced by example patients. |
| **`example_lab_data.sql`** | **Mock lab results** (CBC, BMP, urinalysis, lipids, TSH, HbA1c) for patients 1, 4, 5, and 41. |

## Loading example lab data

1. Load patients first (if you use them):  
   `mysql openemr < example_patient_data.sql`
2. Load lab data (uses `provider_id = 1` and patient IDs 1, 4, 5, 41):  
   `mysql openemr < example_lab_data.sql`

Or run the scripts in the OpenEMR SQL interface in the same order. After loading, lab results will appear in the patient chart for those patients (e.g. Lab Results, FHIR Observation lab endpoints).
