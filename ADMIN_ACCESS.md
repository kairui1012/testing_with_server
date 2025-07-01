# Admin Dashboard Access

## Super URL for Admin Dashboard

The admin dashboard can be accessed using the following secure URL:

**Admin Dashboard URL:**
```
http://your-domain.com/admin-xyz123
```

**Local Development URL:**
```
http://localhost:8000/admin-xyz123
```
```

## Features

### Dashboard Overview
- **Recent Week**: Displays feedback submissions organized by week
- **Filter Options**: Filter feedback by specific weeks with date ranges
- **Statistics Cards**: 
  - Total Feedbacks
  - This Week's Submissions
  - Feedbacks with Referrer
  - Unique Phone Numbers

### Table Columns
- **Phone**: Phone number of the feedback submitter (with phone icon)
- **Good**: Positive feedback content (truncated to 50 characters)
- **Bad**: Negative feedback content (truncated to 50 characters)  
- **Referrer**: Source of referral (with status badges)
- **Remark**: Additional remarks or notes
- **Week**: Week number with date range (e.g., "Week 26 (Jun 24 - Jun 30)")
- **Date**: Submission date and time

### Filter Functionality
- **All Weeks**: Show all feedback submissions
- **Specific Week**: Filter by individual weeks with date ranges
- **Auto-submit**: Filter updates automatically when selection changes

### Responsive Design
- Mobile-friendly interface
- Clean, modern design using Tailwind CSS
- Hover effects and smooth transitions
- Pagination for large datasets

## Security Notes
- This is a super URL without authentication requirements
- Keep the URL private and secure
- Consider adding IP restrictions if needed
- Change the URL path if security is compromised

## Data Structure
The dashboard displays feedback data with the following fields:
- Phone number (contact information)
- Good feedback (positive comments)
- Bad feedback (areas for improvement)
- Referrer (source of the feedback)
- Remark (additional notes)
- Week (ISO week format)
- Creation timestamp

## Usage Tips
1. Use the week filter to focus on specific time periods
2. Click on truncated text to see full content
3. Use "See all" to reset filters
4. Pagination controls appear when there are more than 10 records
5. Statistics cards provide quick insights at the top
