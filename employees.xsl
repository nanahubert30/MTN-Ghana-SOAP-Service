<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
                 xmlns:emp="http://mtn.ghana.com/employees">
    
    <xsl:template match="/">
        <html>
            <head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title>MTN Ghana Employee Directory</title>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        padding: 20px;
                    }
                    
                    .container {
                        max-width: 1200px;
                        margin: 0 auto;
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                        overflow: hidden;
                    }
                    
                    .header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 40px;
                        text-align: center;
                    }
                    
                    .header h1 {
                        font-size: 2.5em;
                        margin-bottom: 10px;
                    }
                    
                    .header p {
                        font-size: 1.1em;
                        opacity: 0.9;
                    }
                                        curl http://localhost/WSS/server.php?wsdl
                    .content {
                        padding: 40px;
                    }
                    
                    .stats {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 20px;
                        margin-bottom: 40px;
                    }
                    
                    .stat-card {
                        background: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        border-left: 4px solid #667eea;
                        text-align: center;
                    }
                    
                    .stat-card h3 {
                        color: #333;
                        font-size: 0.95em;
                        margin-bottom: 10px;
                        text-transform: uppercase;
                    }
                    
                    .stat-card .number {
                        font-size: 2em;
                        color: #667eea;
                        font-weight: bold;
                    }
                    
                    .employees-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                        gap: 20px;
                        margin-top: 30px;
                    }
                    
                    .employee-card {
                        background: white;
                        border: 1px solid #dee2e6;
                        border-radius: 8px;
                        padding: 20px;
                        transition: transform 0.3s, box-shadow 0.3s;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    
                    .employee-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
                    }
                    
                    .employee-header {
                        border-bottom: 2px solid #667eea;
                        padding-bottom: 15px;
                        margin-bottom: 15px;
                    }
                    
                    .employee-name {
                        font-size: 1.3em;
                        font-weight: bold;
                        color: #333;
                        margin-bottom: 5px;
                    }
                    
                    .employee-position {
                        color: #667eea;
                        font-weight: 600;
                        margin-bottom: 5px;
                    }
                    
                    .employee-department {
                        color: #666;
                        font-size: 0.9em;
                    }
                    
                    .employee-info {
                        display: flex;
                        align-items: center;
                        margin-bottom: 10px;
                        font-size: 0.9em;
                    }
                    
                    .employee-info .label {
                        color: #666;
                        min-width: 80px;
                        font-weight: 600;
                    }
                    
                    .employee-info .value {
                        color: #333;
                        margin-left: 10px;
                    }
                    
                    .status-badge {
                        display: inline-block;
                        padding: 5px 12px;
                        border-radius: 20px;
                        font-size: 0.85em;
                        font-weight: 600;
                        margin-top: 10px;
                    }
                    
                    .status-active {
                        background: #d4edda;
                        color: #155724;
                    }
                    
                    .status-inactive {
                        background: #f8d7da;
                        color: #721c24;
                    }
                    
                    .salary-info {
                        background: #e7f3ff;
                        border-left: 3px solid #2196F3;
                        padding: 10px;
                        border-radius: 3px;
                        margin-top: 15px;
                        font-weight: 600;
                        color: #0c5aa0;
                    }
                    
                    .table-view {
                        margin-top: 40px;
                    }
                    
                    .table-view h2 {
                        color: #333;
                        margin-bottom: 20px;
                        font-size: 1.5em;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        background: white;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    
                    table thead {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                    }
                    
                    table th {
                        padding: 15px;
                        text-align: left;
                        font-weight: 600;
                    }
                    
                    table td {
                        padding: 12px 15px;
                        border-bottom: 1px solid #dee2e6;
                    }
                    
                    table tbody tr:hover {
                        background: #f8f9fa;
                    }
                    
                    .footer {
                        background: #f8f9fa;
                        padding: 20px;
                        text-align: center;
                        border-top: 1px solid #dee2e6;
                        color: #666;
                        font-size: 0.9em;
                        margin-top: 40px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>📱 MTN Ghana Employee Directory</h1>
                        <p>Comprehensive Staff Management System</p>
                    </div>
                    
                    <div class="content">
                        <!-- Statistics -->
                        <div class="stats">
                            <div class="stat-card">
                                <h3>Total Employees</h3>
                                <div class="number"><xsl:value-of select="count(emp:employees/emp:employee)"/></div>
                            </div>
                            <div class="stat-card">
                                <h3>Active Employees</h3>
                                <div class="number"><xsl:value-of select="count(emp:employees/emp:employee[emp:status='active'])"/></div>
                            </div>
                            <div class="stat-card">
                                <h3>Inactive Employees</h3>
                                <div class="number"><xsl:value-of select="count(emp:employees/emp:employee[emp:status='inactive'])"/></div>
                            </div>
                            <div class="stat-card">
                                <h3>Total Departments</h3>
                                <div class="number">
                                    <xsl:value-of select="count(emp:employees/emp:employee[not(emp:department=preceding-sibling::emp:employee/emp:department)])"/>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card View -->
                        <h2 style="margin-top: 30px; color: #333;">Employee Cards</h2>
                        <div class="employees-grid">
                            <xsl:for-each select="emp:employees/emp:employee">
                                <xsl:sort select="emp:lastName"/>
                                <div class="employee-card">
                                    <div class="employee-header">
                                        <div class="employee-name">
                                            <xsl:value-of select="emp:firstName"/> <xsl:value-of select="emp:lastName"/>
                                        </div>
                                        <div class="employee-position"><xsl:value-of select="emp:position"/></div>
                                        <div class="employee-department"><xsl:value-of select="emp:department"/></div>
                                    </div>
                                    
                                    <div class="employee-info">
                                        <span class="label">Email:</span>
                                        <span class="value"><xsl:value-of select="emp:email"/></span>
                                    </div>
                                    
                                    <div class="employee-info">
                                        <span class="label">Phone:</span>
                                        <span class="value"><xsl:value-of select="emp:phone"/></span>
                                    </div>
                                    
                                    <div class="employee-info">
                                        <span class="label">Joined:</span>
                                        <span class="value"><xsl:value-of select="emp:joinDate"/></span>
                                    </div>
                                    
                                    <div class="salary-info">
                                        GHS <xsl:value-of select="format-number(emp:salary, '#,###.00')"/>
                                    </div>
                                    
                                    <xsl:choose>
                                        <xsl:when test="emp:status = 'active'">
                                            <span class="status-badge status-active">✓ Active</span>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <span class="status-badge status-inactive">⊗ Inactive</span>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </div>
                            </xsl:for-each>
                        </div>
                        
                        <!-- Table View -->
                        <div class="table-view">
                            <h2>Employee Roster</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <xsl:for-each select="emp:employees/emp:employee">
                                        <xsl:sort select="emp:lastName"/>
                                        <tr>
                                            <td><xsl:value-of select="emp:id"/></td>
                                            <td><xsl:value-of select="emp:firstName"/> <xsl:value-of select="emp:lastName"/></td>
                                            <td><xsl:value-of select="emp:position"/></td>
                                            <td><xsl:value-of select="emp:department"/></td>
                                            <td><xsl:value-of select="emp:email"/></td>
                                            <td><xsl:value-of select="emp:phone"/></td>
                                            <td>GHS <xsl:value-of select="format-number(emp:salary, '#,###.00')"/></td>
                                            <td>
                                                <xsl:choose>
                                                    <xsl:when test="emp:status = 'active'">
                                                        <span class="status-badge status-active">Active</span>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <span class="status-badge status-inactive">Inactive</span>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </td>
                                        </tr>
                                    </xsl:for-each>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="footer">
                        <p>MTN Ghana Employee Directory | Generated from XML with XSL Transformation</p>
                        <p>© 2026 MTN Ghana. All rights reserved.</p>
                    </div>
                </div>
            </body>
        </html>
    </xsl:template>
    
</xsl:stylesheet>
