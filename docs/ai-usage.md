# AI Usage Documentation

## Overview
This document tracks how AI tools were used in the development process, what modifications were made, and what was learned.

## Team Information
- **Project:** Musanze Market Order System
- **Date:** February 2026
- **Group:** Group #1

## AI Tools Used
- ChatGPT (Code generation, problem-solving)
- GitHub Copilot (Code completion)
- Claude (Documentation assistance)

## AI Usage Log

### Phase 1: Planning

#### Prompt Used:
"Generate user stories for a potato market order management system in Rwanda"

**What AI Provided:**
- 8 user stories with acceptance criteria
- Stakeholder identification
- Non-functional requirements

**What We Changed:**
- Adapted to local context (Musanze specific)
- Added mobile-first considerations
- Simplified some stories to match project scope

**What We Learned:**
- Importance of context in user stories
- How to break down features into manageable chunks

### Phase 2: Design

#### Prompt Used:
"Create wireframe descriptions for a responsive order management dashboard"

**What AI Provided:**
- Desktop and mobile layout suggestions
- Component placement recommendations
- Color scheme proposals

**What We Changed:**
- Customized colors to match Rwandan market context
- Added local language considerations
- Simplified navigation for mobile users

**What We Learned:**
- Responsive design principles
- Importance of user flow in rural contexts

### Phase 3: Development

#### Prompt Used:
"Generate PHP MVC code for order management with MySQLi"

**What AI Provided:**
- Basic CRUD operations
- Database schema
- Controller structure

**What We Changed:**
- Added prepared statements for security
- Implemented proper error handling
- Added validation for Rwandan phone numbers
- Customized currency formatting

**What We Learned:**
- Security best practices
- MySQLi prepared statements
- MVC architecture implementation

#### Prompt Used:
"Create JavaScript for live calculation of order totals"

**What AI Provided:**
- Basic calculator function
- Event listeners

**What We Changed:**
- Added proper number formatting for RWF
- Enhanced validation
- Added accessibility features

**What We Learned:**
- DOM manipulation
- Real-time validation
- Currency formatting in JavaScript

### Phase 4: Testing

#### Prompt Used:
"Generate test cases for order management system"

**What AI Provided:**
- 15 test cases covering main functionality

**What We Changed:**
- Added specific test data for Rwanda context
- Included phone number format tests
- Added performance test cases

**What We Learned:**
- Test-driven development approach
- Edge case identification
- Testing strategies

## Lessons Learned

### Technical Lessons:
1. **Database Design**: Importance of proper indexing for performance
2. **Security**: Always use prepared statements, never trust user input
3. **Validation**: Both client and server-side validation are necessary
4. **Responsive Design**: Mobile-first approach works best for local context

### Project Management Lessons:
1. **Documentation**: AI helps generate initial docs, but team must customize
2. **Version Control**: 25+ commits show progressive development
3. **Team Communication**: Clear role distribution essential

## AI Limitations Identified
1. **Context Understanding**: AI doesn't fully understand local business practices
2. **Security Awareness**: Generated code needed security hardening
3. **UI/UX**: Generated designs needed cultural adaptation
4. **Testing**: AI test cases needed real-world scenario adjustment

## Final Thoughts
AI tools significantly accelerated development but required human oversight for:
- Security considerations
- Local context adaptation
- Business logic validation
- User experience refinement

The balance of AI assistance and human expertise produced a robust, context-appropriate solution for Musanze potato aggregators.