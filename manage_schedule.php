import java.util.*;

public class Main {
    static final int MAX_POINTS = 20;

    // Returns x-value of point of intersection of two lines
    static int x_intersect(int x1, int y1, int x2, int y2, int x3, int y3, int x4, int y4) {
        int num = (x1 * y2 - y1 * x2) * (x3 - x4) - (x1 - x2) * (x3 * y4 - y3 * x4);
        int den = (x1 - x2) * (y3 - y4) - (y1 - y2) * (x3 - x4);
        return num / den;
    }

    // Returns y-value of point of intersection of two lines
    static int y_intersect(int x1, int y1, int x2, int y2, int x3, int y3, int x4, int y4) {
        int num = (x1 * y2 - y1 * x2) * (y3 - y4) - (y1 - y2) * (x3 * y4 - y3 * x4);
        int den = (x1 - x2) * (y3 - y4) - (y1 - y2) * (x3 - x4);
        return num / den;
    }

    // This functions clips all the edges w.r.t one clip edge of clipping area
    static void clip(ArrayList<int[]> poly_points, int x1, int y1, int x2, int y2) {
        ArrayList<int[]> new_points = new ArrayList<>();

        for (int i = 0; i < poly_points.size(); i++) {
            // i and k form a line in polygon
            int k = (i + 1) % poly_points.size();
            int ix = poly_points.get(i)[0], iy = poly_points.get(i)[1];
            int kx = poly_points.get(k)[0], ky = poly_points.get(k)[1];

            // Calculating position of first point w.r.t. clipper line
            int i_pos = (x2 - x1) * (iy - y1) - (y2 - y1) * (ix - x1);

            // Calculating position of second point w.r.t. clipper line
            int k_pos = (x2 - x1) * (ky - y1) - (y2 - y1) * (kx - x1);

            // Case 1 : When both points are inside
            if (i_pos < 0 && k_pos < 0) {
                //Only second point is added
                new_points.add(new int[]{kx, ky});
            }

            // Case 2: When only first point is outside
            else if (i_pos >= 0 && k_pos < 0) {
                // Point of intersection with edge and the second point is added
                new_points.add(new int[]{x_intersect(x1, y1, x2, y2, ix, iy, kx, ky), y_intersect(x1, y1, x2, y2, ix, iy, kx, ky)});
                new_points.add(new int[]{kx, ky});
            }

            // Case 3: When only second point is outside
            else if (i_pos < 0 && k_pos >= 0) {
                //Only point of intersection with edge is added
                new_points.add(new int[]{x_intersect(x1, y1, x2, y2, ix, iy, kx, ky), y_intersect(x1, y1, x2, y2, ix, iy, kx, ky)});
            }

            // Case 4: When both points are outside
            else {
                //No points are added
            }
        }

        // Copying new points into original array and changing the no. of vertices
        poly_points.clear();
        poly_points.addAll(new_points);
    }

    // Implements Sutherland–Hodgman algorithm
    static void suthHodgClip(ArrayList<int[]> poly_points, ArrayList<int[]> clipper_points) {
        //i and k are two consecutive indexes
        for (int i = 0; i < clipper_points.size(); i++) {
            int k = (i + 1) % clipper_points.size();

            // We pass the current array of vertices, it's size and the end points of the selected clipper line
            clip(poly_points, clipper_points.get(i)[0], clipper_points.get(i)[1], clipper_points.get(k)[0], clipper_points.get(k)[1]);
        }

        // Printing vertices of clipped polygon
        for (int[] point : poly_points)
            System.out.println("(" + point[0] + ", " + point[1] + ")");
    }

    //Driver code
    public static void main(String[] args) {
        // Defining polygon vertices in clockwise order
        ArrayList<int[]> poly_points = new ArrayList<>(Arrays.asList(new int[]{100, 150}, new int[]{200, 250}, new int[]{300, 200}));

        // Defining clipper polygon vertices in clockwise order
        // 1st Example with square clipper
        ArrayList<int[]> clipper_points = new ArrayList<>(Arrays.asList(new int[]{150, 150}, new int[]{150, 200}, new int[]{200, 200}, new int[]{200, 150}));

        // 2nd Example with triangle clipper
        // ArrayList<int[]> clipper_points = new ArrayList<>(Arrays.asList(new int[]{100, 300}, new int[]{300, 300}, new int[]{200, 100}));

        //Calling the clipping function
        suthHodgClip(poly_points, clipper_points);
    }
}