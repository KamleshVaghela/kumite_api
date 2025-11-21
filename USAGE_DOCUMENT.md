# Karate Competition Management System - Usage Document

## Table of Contents
1. [System Overview](#1-system-overview)
2. [Getting Started](#2-getting-started)
3. [User Roles and Permissions](#3-user-roles-and-permissions)
4. [Competition Management](#4-competition-management)
5. [Participant Management](#5-participant-management)
6. [Bout Generation](#6-bout-generation)
7. [Result Management](#7-result-management)
8. [PDF Generation](#8-pdf-generation)
9. [Data Import/Export](#9-data-importexport)
10. [External Competitions](#10-external-competitions)
11. [Troubleshooting](#11-troubleshooting)
12. [Best Practices](#12-best-practices)

## 1. System Overview

The Karate Competition Management System (Kumite API) is a comprehensive web application designed to streamline the management of karate competitions. The system supports both Kumite (sparring) and Kata (forms) competitions, providing end-to-end functionality from participant registration to certificate generation.

### 1.1 Key Features
- **Competition Management**: Create and manage multiple types of karate competitions
- **Participant Registration**: Bulk and individual participant registration with data validation
- **Automated Bout Generation**: Create tournament brackets automatically
- **Result Tracking**: Record and manage competition results
- **PDF Generation**: Generate certificates, bout sheets, and reports
- **Data Management**: Import/export data using Excel files
- **Multi-level Competitions**: Support from Dojo level to International competitions

## 2. Getting Started

### 2.1 System Access
1. Open your web browser and navigate to the system URL
2. Click on the "Login" button
3. Enter your credentials (username and password)
4. Click "Sign In" to access the dashboard

### 2.2 Dashboard Overview
After successful login, you'll see the main dashboard with:
- **Navigation Menu**: Access to different system modules
- **Quick Stats**: Overview of current competitions and participants
- **Recent Activities**: Latest system activities and updates
- **Shortcuts**: Quick access to frequently used functions

### 2.3 Navigation Structure
```
Admin Dashboard
├── Competition Management
│   ├── Create Competition
│   ├── Manage Competitions
│   └── Competition Reports
├── Participant Management
│   ├── Register Participants
│   ├── Import/Export Data
│   └── Participant Reports
├── Bout Management
│   ├── Generate Bouts
│   ├── Manage Bout Results
│   └── Print Bout Sheets
├── External Competitions
│   ├── External Bout Generation
│   └── External Kata Management
└── Reports & Certificates
    ├── Generate Certificates
    ├── Competition Reports
    └── Statistical Reports
```

## 3. User Roles and Permissions

### 3.1 Administrator
**Full Access Permissions:**
- Create and manage competitions
- Register and manage participants
- Generate bouts and manage results
- Access all reports and statistics
- Manage user accounts and permissions
- System configuration and settings

### 3.2 Competition Organizer
**Limited Access Permissions:**
- Create competitions (with approval)
- Register participants for assigned competitions
- Generate bouts for assigned competitions
- Record results for ongoing competitions
- Generate certificates and reports

### 3.3 Coach
**View and Download Permissions:**
- View competitions their students are registered for
- Download bout sheets for their participants
- View results and rankings
- Download certificates for their students
- Generate team performance reports

## 4. Competition Management

### 4.1 Creating a New Competition

#### Step 1: Navigate to Competition Creation
1. From the dashboard, click on "Competition Management"
2. Select "Create Competition"
3. Fill in the competition details form

#### Step 2: Basic Competition Information
```
Competition Details Form:
- Competition Name: [Enter descriptive name]
- Competition Level: [Select from dropdown]
  * IDJ - Inter-Dojo
  * ISC - Inter-School
  * IDS - Inter-District
  * D - District
  * IST - Inter-State
  * S - State
  * N - National
  * I - International
- Competition Type: [Kumite/Kata/Both]
- Start Date: [Select date]
- End Date: [Select date]
- Venue: [Enter venue details]
- Description: [Optional description]
```

#### Step 3: Category Configuration
1. Define age groups (e.g., Under-12, Under-14, Under-16, etc.)
2. Set weight categories for each age group
3. Configure gender divisions (Male/Female/Mixed)
4. Set belt/rank requirements if applicable

#### Step 4: Save and Activate
1. Review all entered information
2. Click "Save Competition"
3. Activate the competition for participant registration

### 4.2 Managing Existing Competitions

#### Viewing Competitions
1. Navigate to "Competition Management" → "Manage Competitions"
2. View list of all competitions with status indicators:
   - **Draft**: Competition created but not active
   - **Active**: Open for registration
   - **In Progress**: Competition ongoing
   - **Completed**: Competition finished

#### Editing Competition Details
1. Click on the competition name or "Edit" button
2. Modify required fields
3. Save changes (Note: Some fields may be locked during active competitions)

#### Competition Status Management
- **Activate**: Open competition for participant registration
- **Pause**: Temporarily stop new registrations
- **Close Registration**: Stop accepting new participants
- **Start Competition**: Begin bout generation and competition phase
- **Complete**: Finalize competition and generate final reports

## 5. Participant Management

### 5.1 Individual Participant Registration

#### Step 1: Access Registration Form
1. Navigate to "Participant Management" → "Register Participants"
2. Select the target competition
3. Click "Add New Participant"

#### Step 2: Enter Participant Details
```
Participant Information Form:
Personal Details:
- Full Name: [First Name Last Name]
- Gender: [Male/Female]
- Date of Birth: [DD/MM/YYYY]
- Age: [Auto-calculated or manual entry]
- Weight: [In kilograms]

Martial Arts Details:
- Belt/Rank: [Select from dropdown]
- School/Dojo: [Select or enter manually]
- Coach Name: [Select from registered coaches]
- Years of Training: [Optional]

Competition Details:
- Competition Category: [Auto-suggested based on age/weight]
- Special Requirements: [Medical conditions, etc.]
- Emergency Contact: [Name and phone number]
```

#### Step 3: Validation and Confirmation
1. System validates all required fields
2. Checks for duplicate registrations
3. Confirms category placement
4. Generates unique participant ID

### 5.2 Bulk Participant Registration

#### Step 1: Prepare Excel Template
1. Download the participant template from "Import/Export" section
2. Excel template includes columns:
   - External Unique ID
   - Full Name
   - Gender (M/F)
   - Age
   - Weight
   - Rank/Belt Level
   - School/Dojo Code
   - Coach Code
   - Category (optional - auto-assigned if blank)

#### Step 2: Fill Template Data
```
Excel Template Structure:
| External_ID | Full_Name | Gender | Age | Weight | Rank | School_Code | Coach_Code |
|-------------|-----------|--------|-----|--------|------|-------------|------------|
| P001        | John Doe  | M      | 12  | 40     | 8    | SCH001      | C001       |
| P002        | Jane Smith| F      | 11  | 35     | 7    | SCH002      | C002       |
```

#### Step 3: Upload and Process
1. Navigate to "Import/Export" → "Import Participants"
2. Select the competition
3. Upload completed Excel file
4. Review data validation report
5. Confirm import or fix errors

### 5.3 Participant Data Management

#### Searching and Filtering
- **Search by Name**: Enter participant name in search box
- **Filter by Category**: Select age group, weight class, or gender
- **Filter by School**: View participants from specific schools
- **Filter by Status**: Active, Withdrawn, etc.

#### Editing Participant Information
1. Find participant using search/filter
2. Click "Edit" button next to participant name
3. Modify allowed fields (restrictions may apply after bout generation)
4. Save changes and note modification timestamp

#### Participant Status Management
- **Active**: Participant is registered and eligible for competition
- **Withdrawn**: Participant has withdrawn (with reason)
- **Disqualified**: Participant disqualified (with reason)
- **Medical Hold**: Temporary medical restriction

## 6. Bout Generation

### 6.1 Kumite (Sparring) Bout Generation

#### Step 1: Pre-Generation Setup
1. Ensure all participants are properly registered
2. Verify category assignments are complete
3. Navigate to "Bout Management" → "Generate Kumite Bouts"
4. Select the competition

#### Step 2: Generate Bouts Automatically
1. Click "Auto-Generate Bouts" button
2. System processes participants by category
3. Creates elimination brackets automatically
4. Assigns bout numbers and sequences

#### Step 3: Review Generated Bouts
- **Bout List View**: See all generated bouts in tabular format
- **Bracket View**: Visual tournament bracket display
- **Category Breakdown**: Bouts organized by category
- **Participant Distribution**: Verify even distribution

#### Manual Bout Adjustments
1. Select specific bout to modify
2. Change participant assignments if needed
3. Adjust bout timing or sequence
4. Add special notes or requirements

### 6.2 Kata (Forms) Bout Generation

#### Step 1: Kata Competition Setup
1. Navigate to "Kata Management" → "Generate Kata Bouts"
2. Select competition and kata categories
3. Define scoring criteria (if not using defaults)

#### Step 2: Generate Kata Sessions
1. Group participants by category
2. Create kata performance sessions
3. Assign judges and scoring panels
4. Set performance order and timing

#### Kata Scoring Configuration
- **Individual Kata**: Each participant performs individually
- **Team Kata**: Group performances (if applicable)
- **Scoring System**: Configure scoring criteria and weight distribution

### 6.3 Custom Bout Management

#### Creating Custom Bouts
1. Navigate to "Bout Management" → "Custom Bouts"
2. Click "Create Custom Bout"
3. Manually select participants
4. Set bout rules and conditions
5. Schedule bout timing

#### Special Tournament Formats
- **Round Robin**: All participants compete against each other
- **Pool Play**: Group stage followed by elimination
- **Seeded Tournament**: Ranked participants with seeded placement
- **Exhibition Matches**: Non-competitive demonstration bouts

## 7. Result Management

### 7.1 Recording Bout Results

#### During Competition
1. Navigate to "Bout Management" → "Record Results"
2. Select the active bout
3. Enter result information:
   ```
   Bout Result Form:
   - Winner: [Select participant]
   - Result Type: [Decision/KO/TKO/Disqualification]
   - Score: [If applicable]
   - Duration: [Bout length]
   - Notes: [Additional comments]
   - Judge Signatures: [Digital approval]
   ```

#### Result Validation
- System checks for logical consistency
- Validates against competition rules
- Requires judge confirmation
- Creates audit trail for all entries

### 7.2 Managing Competition Results

#### Result Entry Methods
1. **Live Entry**: Real-time result recording during competition
2. **Batch Entry**: Upload results from external scoring systems
3. **Mobile Entry**: Results entered via mobile interface (if available)

#### Result Corrections
1. Navigate to specific bout result
2. Click "Edit Result" (requires authorization)
3. Enter correction details and justification
4. Obtain supervisor approval for changes
5. Update audit log with correction reason

### 7.3 Ranking and Medal Distribution

#### Automatic Ranking Generation
- System calculates rankings based on results
- Handles tie-breaking scenarios
- Updates rankings in real-time
- Generates medal distribution lists

#### Medal Categories
- **Gold Medal**: First place winners
- **Silver Medal**: Second place (runners-up)
- **Bronze Medal**: Third place (semi-final losers in elimination format)
- **Participation**: All registered participants

## 8. PDF Generation

### 8.1 Bout Sheet Generation

#### Generating Individual Bout Sheets
1. Navigate to "Reports" → "Bout Sheets"
2. Select competition and specific bouts
3. Choose bout sheet template
4. Click "Generate PDF"

#### Bout Sheet Information Includes:
- Bout number and category
- Participant names and details
- Competition information
- Judge signature areas
- Result recording section
- Time and date stamps

#### Bulk Bout Sheet Generation
1. Select "Generate All Bout Sheets"
2. Choose categories or entire competition
3. System generates comprehensive PDF package
4. Download or send to printer queue

### 8.2 Certificate Generation

#### Winner Certificates
1. Ensure all results are finalized
2. Navigate to "Certificates" → "Winner Certificates"
3. Select competition and medal categories
4. Choose certificate template
5. Generate PDF certificates

#### Certificate Customization Options:
- **Competition Logo**: Upload and position logos
- **Signature Areas**: Add official signatures
- **Border Designs**: Select decorative borders
- **Font Styles**: Choose appropriate fonts
- **Language Options**: English/Hindi text options

#### Participation Certificates
1. Navigate to "Certificates" → "Participation Certificates"
2. Select all participants or specific categories
3. Batch generate participation certificates
4. Include participant-specific information

### 8.3 Competition Reports

#### Comprehensive Competition Reports
```
Report Types Available:
1. Overall Results Summary
2. Category-wise Results
3. School/Team Performance Reports
4. Coach Performance Summary
5. Statistical Analysis Report
6. Financial Summary (if applicable)
7. Participation Analytics
```

#### Custom Report Generation
1. Navigate to "Reports" → "Custom Reports"
2. Select data parameters and filters
3. Choose report format and layout
4. Generate and download PDF report

## 9. Data Import/Export

### 9.1 Data Import Procedures

#### Excel Import Preparation
1. Download appropriate template from system
2. Ensure data follows exact column format
3. Validate data completeness before upload
4. Remove any formatting or formulas

#### Import Process
1. Navigate to "Data Management" → "Import Data"
2. Select data type (Participants, Results, etc.)
3. Choose target competition
4. Upload prepared Excel file
5. Review validation report
6. Confirm import or address errors

#### Common Import Issues and Solutions:
- **Missing Required Fields**: Ensure all mandatory columns have data
- **Invalid Data Format**: Check date, number, and text formats
- **Duplicate Entries**: System will flag duplicate participant registrations
- **Category Mismatches**: Verify age/weight categories are valid

### 9.2 Data Export Options

#### Participant Data Export
1. Navigate to "Data Management" → "Export Data"
2. Select competition and participant filters
3. Choose export format (Excel, CSV, PDF)
4. Download generated file

#### Results Export
- **Competition Results**: Final standings and medal winners
- **Detailed Bout Results**: Individual bout outcomes and scores
- **Statistical Data**: Performance analytics and trends
- **Financial Reports**: Registration fees and expenses (if applicable)

#### Export Formats Available:
- **Excel (.xlsx)**: Full formatting and formulas
- **CSV (.csv)**: Raw data for external processing
- **PDF**: Formatted reports for printing and sharing

## 10. External Competitions

### 10.1 External Competition Setup

#### Creating External Competitions
1. Navigate to "External Competitions" → "Create External Competition"
2. External competitions operate independently from main system
3. Separate participant pool and bout management
4. Integration with main federation database for record keeping

#### External vs Internal Competitions:
- **Internal**: Managed within federation system
- **External**: Independent competitions with data sync
- **Reporting**: Separate reporting chains
- **Participants**: May include non-federation members

### 10.2 External Bout Management

#### External Kumite Bouts
1. Access "External Competitions" → "External Bout Generation"
2. Import participant data from external sources
3. Generate bouts using same logic as internal competitions
4. Export results for external reporting

#### External Kata Management
1. Navigate to "External Competitions" → "External Kata Management"
2. Manage kata competitions for external events
3. Separate scoring and result tracking
4. Generate certificates and reports

### 10.3 Data Synchronization

#### Federation Database Sync
- Periodic synchronization with main federation database
- Participant validation against federation records
- Result reporting to higher-level authorities
- Maintenance of local competition autonomy

## 11. Troubleshooting

### 11.1 Common Issues and Solutions

#### Login and Access Issues
**Problem**: Cannot log into the system
**Solutions**:
1. Verify username and password are correct
2. Check if account is active and not suspended
3. Clear browser cache and cookies
4. Try different browser or incognito mode
5. Contact system administrator if issues persist

**Problem**: Access denied to specific features
**Solutions**:
1. Verify user role has appropriate permissions
2. Check if feature is available for current competition status
3. Contact administrator for permission updates

#### Data Entry Issues
**Problem**: Cannot save participant information
**Solutions**:
1. Check all required fields are completed
2. Verify data format (dates, numbers, text)
3. Ensure participant is not already registered
4. Check category assignments are valid
5. Try refreshing page and re-entering data

**Problem**: Excel import fails
**Solutions**:
1. Verify Excel file follows exact template format
2. Check for empty rows or invalid characters
3. Ensure file size is within limits
4. Remove any formatting, formulas, or merged cells
5. Save file in .xlsx format

#### PDF Generation Issues
**Problem**: PDF files not generating or corrupting
**Solutions**:
1. Check if all required data is complete
2. Verify templates are properly configured
3. Clear browser cache and try again
4. Contact administrator if templates are missing
5. Try generating smaller batches if processing large volumes

### 11.2 Performance Issues

#### Slow System Response
**Causes and Solutions**:
1. **High user load**: Wait for peak usage to reduce, or contact administrator
2. **Large data sets**: Use filters to reduce data volume
3. **Network connectivity**: Check internet connection stability
4. **Browser issues**: Close unnecessary tabs, restart browser

#### Bout Generation Taking Too Long
**Solutions**:
1. Ensure participant data is complete and validated
2. Generate bouts in smaller category batches
3. Check system resources during off-peak hours
4. Contact administrator if consistent delays occur

### 11.3 Data Issues

#### Missing or Incorrect Data
**Solutions**:
1. **Missing participants**: Check import logs for errors
2. **Incorrect categories**: Verify age/weight calculations
3. **Duplicate entries**: Use search function to identify duplicates
4. **Result discrepancies**: Check audit logs for data modifications

#### Data Recovery
**Process**:
1. Contact system administrator immediately
2. Provide specific details about missing/corrupted data
3. Administrator will check backup systems
4. Follow recovery procedures as directed
5. Verify restored data accuracy

## 12. Best Practices

### 12.1 Competition Setup Best Practices

#### Pre-Competition Planning
1. **Timeline Management**:
   - Set up competition at least 2 weeks before event
   - Allow 1 week for participant registration
   - Generate bouts 2-3 days before competition
   - Test all systems 1 day before event

2. **Data Validation**:
   - Verify all participant information before bout generation
   - Check category assignments for accuracy
   - Validate judge and official assignments
   - Test PDF generation for all required documents

3. **Backup Procedures**:
   - Export participant data before bout generation
   - Create PDF backups of all bout sheets
   - Maintain offline copies of critical information
   - Establish communication plan for technical issues

### 12.2 During Competition Best Practices

#### Real-Time Management
1. **Result Entry**:
   - Enter results immediately after each bout
   - Verify result accuracy before final submission
   - Maintain paper backup of critical results
   - Regular system status checks

2. **Technical Support**:
   - Designate technical support person on-site
   - Have backup devices available for result entry
   - Maintain internet connectivity backup options
   - Keep administrator contact information readily available

### 12.3 Post-Competition Best Practices

#### Data Management
1. **Final Verification**:
   - Review all results for accuracy
   - Verify medal distribution is correct
   - Check certificate generation for all winners
   - Export final competition data

2. **Documentation**:
   - Generate comprehensive competition report
   - Archive all competition documents
   - Backup results to external storage
   - Share results with relevant stakeholders

### 12.4 Security Best Practices

#### Data Protection
1. **Access Control**:
   - Use strong passwords and change regularly
   - Log out when leaving computer unattended
   - Limit access to authorized personnel only
   - Monitor user activity for unusual patterns

2. **Data Backup**:
   - Regular automated backups
   - Test backup restoration procedures
   - Maintain offline backup copies
   - Document backup and recovery procedures

### 12.5 User Training Recommendations

#### Administrator Training
- Complete system functionality overview
- Advanced troubleshooting procedures
- User management and permission settings
- Database backup and recovery procedures
- Report generation and customization

#### Organizer Training
- Competition setup and management
- Participant registration procedures
- Bout generation and management
- Result entry and validation
- Basic troubleshooting skills

#### Coach Training
- System navigation and access
- Viewing participant information
- Downloading certificates and reports
- Understanding competition status updates

---

**Document Version**: 1.0  
**Last Updated**: October 19, 2025  
**For Technical Support**: Contact System Administrator  
**For Training**: Contact Federation Training Department