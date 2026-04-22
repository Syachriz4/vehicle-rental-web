# 📊 Activity Diagram: Vehicle Booking Workflow

## 1. Complete Booking Workflow (Happy Path)

```mermaid
graph TD
    A["👤 User/Admin<br/>Request Booking"] -->|Fill form<br/>Vehicle + Driver + Dates| B["📋 Create Booking"]
    
    B -->|Auto-generate<br/>Booking Number| C["✅ Booking Created<br/>Status: Pending"]
    
    C -->|2 Approvals<br/>Auto-created| D["📍 Level 1<br/>Supervisor Review"]
    
    D -->|Review &<br/>Approve| E["✅ Level 1<br/>Approved"]
    
    E -->|Next Level| F["📍 Level 2<br/>Manager Review"]
    
    F -->|Review &<br/>Approve| G["✅ Level 2<br/>Approved"]
    
    G -->|Both Approved| H["🟢 Booking Status:<br/>APPROVED"]
    
    H -->|After booking ends| I["🏁 Mark Complete<br/>Enter End KM"]
    
    I -->|Record Fuel| J["⛽ Fuel Consumption<br/>Recorded"]
    
    J -->|Final Status| K["✅ Booking Status:<br/>COMPLETED"]
    
    L["📊 Activity Log<br/>Every Step"]
    
    A -.->|logged| L
    B -.->|logged| L
    E -.->|logged| L
    G -.->|logged| L
    K -.->|logged| L
    
    style K fill:#28a745
    style H fill:#28a745
```

---

## 2. Rejection Flow (Level 1)

```mermaid
graph TD
    A["👤 Booking<br/>Pending Approval"] -->|Supervisor<br/>Reviews| B["❌ Reject<br/>with Reason"]
    
    B -->|Rejection<br/>Recorded| C["🔴 Booking Status:<br/>REJECTED"]
    
    D["📧 Notification<br/>to Requester"]
    
    E["🔄 Requester Can<br/>Create New<br/>Booking"]
    
    C --> D
    D --> E
    
    L["📊 Activity Log<br/>Rejection Event"]
    
    B -.->|logged| L
    C -.->|logged| L
    
    style C fill:#dc3545
```

---

## 3. Rejection Flow (Level 2)

```mermaid
graph TD
    A["✅ Level 1<br/>Approved"] -->|Manager<br/>Reviews| B["❌ Reject<br/>with Reason"]
    
    B -->|Rejection<br/>Recorded| C["🔴 Booking Status:<br/>REJECTED"]
    
    D["📊 Previous<br/>Level 1 Approval<br/>Cancelled"]
    
    C --> D
    
    L["📊 Activity Log<br/>Rejection Event"]
    
    B -.->|logged| L
    C -.->|logged| L
    
    style C fill:#dc3545
```

---

## 4. Approval State Machine

```mermaid
stateDiagram-v2
    [*] --> Pending: Booking Created
    
    Pending --> Pending: Level 1 Pending
    Pending --> Approved: Level 1 & Level 2 Both Approved
    Pending --> Rejected: Level 1 or Level 2 Rejected
    
    Approved --> Completed: Mark Complete
    
    Rejected --> [*]: Booking End
    Completed --> [*]: Booking End
    
    note right of Pending
        Status: PENDING
        Approval 1: PENDING
        Approval 2: PENDING
    end note
    
    note right of Approved
        Status: APPROVED
        Approval 1: APPROVED
        Approval 2: APPROVED
    end note
```

---

## 5. Actor Interaction Diagram

```mermaid
sequenceDiagram
    participant U as User/Admin
    participant B as Booking System
    participant S as Supervisor (L1)
    participant M as Manager (L2)
    participant L as Activity Log
    
    U->>B: Create Booking
    B->>L: Log: Create Booking
    
    B-->>S: Notify: Pending Approval L1
    S->>B: Review Booking
    
    alt Supervisor Approves
        S->>B: Approve (L1)
        B->>L: Log: Level 1 Approved
        B-->>M: Notify: Pending Approval L2
    else Supervisor Rejects
        S->>B: Reject (L1) + Reason
        B->>L: Log: Level 1 Rejected
        B-->>U: Notify: Booking Rejected
    end
    
    M->>B: Review Booking
    
    alt Manager Approves
        M->>B: Approve (L2)
        B->>L: Log: Level 2 Approved
        B-->>U: Notify: Booking Approved
    else Manager Rejects
        M->>B: Reject (L2) + Reason
        B->>L: Log: Level 2 Rejected
        B-->>U: Notify: Booking Rejected
    end
```

---

## 6. System State Transitions

| State | Condition | Next State | Notification |
|-------|-----------|-----------|--------------|
| **PENDING** | Created | Awaiting L1 | Supervisor notified |
| **PENDING** | L1 Approved | Awaiting L2 | Manager notified |
| **APPROVED** | Both approved | Active | Requester notified |
| **APPROVED** | Booking ended | Mark Complete | Requester can enter End KM |
| **COMPLETED** | End KM entered | Final | Booking archived |
| **REJECTED** | L1 rejects | Ended | Requester notified |
| **REJECTED** | L2 rejects | Ended | Requester notified |

---

## 7. Activity Logging Points

Every action logged:

```
✅ Booking Created
   → User ID, Booking Number, Vehicle, Driver
   
✅ Level 1 Approved/Rejected
   → Approver ID, Decision, Comments (if any)
   
✅ Level 2 Approved/Rejected
   → Approver ID, Decision, Comments (if any)
   
✅ Booking Completed
   → End KM, Fuel used, Duration
   
✅ Login/Logout
   → User, IP Address, Timestamp
```

---

## 8. User Hierarchy Visualization

```
        MANAGER (Level 2)
        └─ Supervisor (Level 1)
           ├─ User A
           ├─ User B
           └─ User C
```

**Approval Chain for User A:**
1. User A creates booking
2. **Supervisor** (User A's supervisor) approves L1
3. **Manager** (Supervisor's supervisor) approves L2
4. Booking → APPROVED

---

## Summary

This diagram shows:
- ✅ Happy path (create → approve L1 → approve L2 → complete)
- ✅ Rejection paths (L1 reject, L2 reject)
- ✅ State transitions
- ✅ Actor interactions
- ✅ Activity logging at each step
- ✅ Hierarchical approval structure
