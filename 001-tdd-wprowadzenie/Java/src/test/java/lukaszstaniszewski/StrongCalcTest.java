package lukaszstaniszewski;

import org.junit.Before;
import org.junit.Test;
import static org.junit.Assert.assertEquals;

/**
 * The type Strong calc test.
 */
public class StrongCalcTest {

    private StrongCalc strongCalc;

    /**
     * Sets up.
     */
    @Before
    public void setUp() {
        this.strongCalc = new StrongCalc();
    }

    /**
     * Calc.
     *
     * @throws Exception the exception
     */
    @Test
    public void calc() throws Exception {
        assertEquals(6, this.strongCalc.calc(3));
    }

    /**
     * Negative strong.
     *
     * @throws Exception the exception
     */
    @Test(expected = Exception.class)
    public void negativeStrong() throws Exception {
        this.strongCalc.calc(-3);
    }
}
