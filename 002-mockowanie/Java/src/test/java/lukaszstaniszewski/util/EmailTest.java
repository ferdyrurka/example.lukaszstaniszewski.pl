package lukaszstaniszewski.util;

import org.junit.Before;
import org.junit.Test;
import static org.junit.Assert.assertTrue;

/**
 * The type Email test.
 */
public class EmailTest {

    private Email email;

    /**
     * Sets up.
     *
     * @throws Exception the exception
     */
    @Before
    public void setUp() throws Exception {
        this.email = new Email();
    }

    /**
     * Send true.
     *
     * @throws Exception the exception
     */
    @Test
    public void sendTrue() throws Exception {
        assertTrue(this.email.send("kontakt@lukaszstaniszewski.pl"));
    }

    /**
     * Send false.
     *
     * @throws Exception the exception
     */
    @Test(expected = Exception.class)
    public void sendFalse() throws Exception {
        this.email.send("kontaktlukaszstaniszewski.pl");
    }
}
