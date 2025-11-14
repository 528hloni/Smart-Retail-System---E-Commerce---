import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.common.PDRectangle;
import org.apache.pdfbox.pdmodel.font.PDType1Font;

public class App {

    public static void main(String[] args) {
        generatePDFReport();
    }

    public static void generatePDFReport() {
        String outFilename = "WheelsOfFortune_Report.pdf";

        // Database connection info
        String url = "jdbc:mysql://localhost:3307/wheels_of_fortune_db";
        String user = "root";
        String password = "528_hloni";

        try (Connection conn = DriverManager.getConnection(url, user, password);
             Statement stmt = conn.createStatement();
             PDDocument document = new PDDocument()) {

            // Fetch orders
            String query = "SELECT o.order_id, o.user_id, u.name, u.surname, o.order_date, o.total_amount, o.status, o.payment_status "
                    + "FROM orders o "
                    + "JOIN users u ON o.user_id = u.user_id "
                    + "ORDER BY o.order_date DESC";
            ResultSet rs = stmt.executeQuery(query);

            // Create PDF page
            PDPage page = new PDPage(PDRectangle.A4);
            document.addPage(page);

            try (PDPageContentStream content = new PDPageContentStream(document, page)) {

                // --- Title ---
                content.beginText();
                content.setFont(PDType1Font.TIMES_BOLD, 20);
                content.newLineAtOffset(50, 760);
                content.showText("WHEELS OF FORTUNE - SALES REPORT");
                content.endText();

                // --- Date ---
                content.beginText();
                content.setFont(PDType1Font.TIMES_ROMAN, 11);
                content.newLineAtOffset(50, 740);
                String date = LocalDateTime.now().format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));
                content.showText("Generated on: " + date);
                content.endText();

                // --- Horizontal Line ---
                content.setLineWidth(0.8f);
                content.moveTo(50, 735);
                content.lineTo(545, 735);
                content.stroke();

                // --- Table Header ---
                float startX = 50;
                float startY = 710;
                float leading = 18f;

                content.beginText();
                content.setFont(PDType1Font.TIMES_BOLD, 12);
                content.newLineAtOffset(startX, startY);
                content.showText(String.format("%-10s %-25s %-18s %-12s",
                        "Order ID", "Customer", "Date", "Total (R)"));
                content.endText();

                // --- Data Rows ---
                float y = startY - leading;
                int totalOrders = 0;
                double totalRevenue = 0;

                while (rs.next()) {
                    String orderId = rs.getString("order_id");
                    String customer = rs.getString("name") + " " + rs.getString("surname");
                    String orderDate = rs.getString("order_date");
                    String total = rs.getString("total_amount");

                    content.beginText();
                    content.setFont(PDType1Font.TIMES_ROMAN, 11);
                    content.newLineAtOffset(startX, y);
                    content.showText(String.format("%-10s %-25s %-18s %-12s",
                            orderId, customer, orderDate, total));
                    content.endText();

                    y -= leading;
                    totalOrders++;
                    totalRevenue += Double.parseDouble(total);

                    // Add new page if needed
                    if (y < 50) {
                        content.close();
                        page = new PDPage(PDRectangle.A4);
                        document.addPage(page);
                        y = 760;
                        content.close();
                    }
                }

                // --- Summary ---
                y -= 10;
                content.beginText();
                content.setFont(PDType1Font.TIMES_BOLD, 12);
                content.newLineAtOffset(startX, y);
                content.showText("Summary:");
                content.endText();

                y -= leading;
                content.beginText();
                content.setFont(PDType1Font.TIMES_ROMAN, 11);
                content.newLineAtOffset(startX, y);
                content.showText("Total Orders: " + totalOrders + "    Total Revenue: R " + String.format("%.2f", totalRevenue));
                content.endText();

            }

            document.save(outFilename);
            System.out.println("PDF generated successfully: " + outFilename);

        } catch (Exception e) {
            System.err.println("Error: " + e.getMessage());
            e.printStackTrace();
        }
    }
}